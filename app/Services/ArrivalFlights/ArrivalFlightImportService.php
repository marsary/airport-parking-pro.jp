<?php

namespace App\Services\ArrivalFlights;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\AirportTerminal;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Sun_Parking CSV から到着便（arrival_flights）を「あれば更新・なければ挿入」する。
 *
 * 画面（ArrivalFlightsController::upload）と Artisan コマンド（arrival-flights:import）の両方から使う。
 */
class ArrivalFlightImportService
{
    private const INSERT_CHUNK = 1000;

    public function __construct(
        private SunParkingCsvReader $reader = new SunParkingCsvReader(),
        private ArrivalFlightImportPlanner $planner = new ArrivalFlightImportPlanner(),
    ) {
    }

    /**
     * @param string $path         CSV ファイルのパス
     * @param int    $arrAirportId 到着空港（通常は myOffice()->airport_id）
     * @param bool   $dryRun       true なら DB を変更せず、件数とリストだけ返す
     * @param bool   $force        true なら、マスタにないもの（変換できないもの）があっても NULL で取り込む
     * @return array{status:string, total:int, target:int, inserted:int, updated:int, unchanged:int, errors:array, issues:array}
     *   status: imported（取り込んだ）/ blocked（変換できないものがあり取り込んでいない）/ dry_run（確認のみ）
     */
    public function import(string $path, int $arrAirportId, bool $dryRun = false, bool $force = false): array
    {
        // 5万件規模の既存便をメモリに載せて照合するため、上限が低い環境では引き上げる
        if ($this->memoryLimitBytes() < 512 * 1024 * 1024) {
            @ini_set('memory_limit', '512M');
        }

        $csv = $this->reader->read($path);
        $records = $csv['records'];

        // マスタ（削除されていないもの）。同じコードが複数あれば id の小さいほうを使う
        $airlines = Airline::query()->orderByDesc('id')->pluck('id', 'code')->all();
        $airports = Airport::query()->orderByDesc('id')->pluck('id', 'code')->all();

        $terminals = [];
        $airlineDefault = [];
        $terminalNoById = [];
        AirportTerminal::query()
            ->where('airport_id', $arrAirportId)
            ->orderByDesc('default_flg')->orderBy('id')
            ->get(['id', 'airline_id', 'terminal_id'])
            ->each(function ($t) use (&$terminals, &$airlineDefault, &$terminalNoById) {
                $terminals[$t->airline_id . '|' . $t->terminal_id] ??= $t->id;
                $airlineDefault[$t->airline_id] ??= $t->id;
                $terminalNoById[$t->id] = $t->terminal_id;
            });

        // 到着空港にターミナルが1件も登録されていない＝店舗の空港の設定違いの可能性が高い。
        // このまま進めると全航空会社が「ターミナル未登録」になるため、ここで止める
        if (!$terminals) {
            $code = Airport::withTrashed()->find($arrAirportId)?->code ?? "id {$arrAirportId}";
            throw new RuntimeException(
                "到着空港（{$code}）のターミナルが airport_terminals に1件も登録されていないため、取り込みを中止しました。"
                . '店舗（offices）の airport_id が正しいか確認してください（成田は NRT）。'
            );
        }

        // 既存の到着便（CSV の日付範囲だけ。論理削除済みも含めて照合する）
        $existing = [];
        if ($records) {
            $dates = array_column($records, 'arrive_date');
            DB::table('arrival_flights')
                ->where('arr_airport_id', $arrAirportId)
                ->whereBetween('arrive_date', [min($dates), max($dates)])
                ->select(['id', 'name', 'arrive_date', 'dep_airport_id', 'arrive_time', 'codeshare', 'terminal_id'])
                ->orderBy('id')
                ->chunk(5000, function ($rows) use (&$existing) {
                    foreach ($rows as $row) {
                        $key = SunParkingCsvReader::key((string) $row->name, substr((string) $row->arrive_date, 0, 10));
                        $existing[$key][] = (array) $row;
                    }
                });
        }

        $plan = $this->planner->plan(
            $records, $airlines, $airports, $terminals, $airlineDefault, $terminalNoById, $existing, $csv['duplicates'],
        );

        $updatedRows = array_sum(array_map(fn ($u) => count($u['ids']), $plan['updates']));

        // 変換できないもの（マスタ未登録・読めない行）があれば、force でない限り取り込まない
        $blocked = ($plan['blocking'] || $csv['errors']) && !$force;
        $status = $dryRun ? 'dry_run' : ($blocked ? 'blocked' : 'imported');

        if ($status === 'imported') {
            DB::transaction(function () use ($plan, $arrAirportId) {
                $now = now();

                foreach ($plan['updates'] as $u) {
                    DB::table('arrival_flights')
                        ->whereIn('id', $u['ids'])
                        ->update($u['values'] + ['updated_at' => $now]);
                }

                $rows = array_map(fn ($r) => $r + [
                    'arr_airport_id' => $arrAirportId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $plan['inserts']);

                foreach (array_chunk($rows, self::INSERT_CHUNK) as $chunk) {
                    DB::table('arrival_flights')->insert($chunk);
                }
            });
        }

        return [
            'status' => $status,
            'total' => $csv['total'],
            'target' => count($records),
            'inserted' => count($plan['inserts']),
            'updated' => $updatedRows,
            'unchanged' => $plan['unchanged'],
            'errors' => $csv['errors'],
            'issues' => $plan['issues'],
            'blocking_count' => count(array_filter($plan['issues'], fn ($i) => $i['blocking'])) + count($csv['errors']),
        ];
    }

    private function memoryLimitBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));
        if ($limit === '-1') {
            return PHP_INT_MAX;
        }
        $unit = strtolower(substr($limit, -1));
        $num = (int) $limit;
        return match ($unit) {
            'g' => $num * 1024 ** 3,
            'm' => $num * 1024 ** 2,
            'k' => $num * 1024,
            default => $num,
        };
    }

    /** 確認リストを CSV（Excel で開ける UTF-8 BOM 付き）にする */
    public static function issuesToCsv(array $result): string
    {
        $out = fopen('php://temp', 'r+');
        fwrite($out, "\xEF\xBB\xBF");
        $put = fn (array $row) => fputcsv($out, $row, ',', '"', '');
        $put(['対応', '種別', 'コード', '便数', '到着日（最初）', '到着日（最後）', '関連', '例', 'やること']);
        foreach ($result['issues'] as $i) {
            $put([
                $i['blocking'] ? '要対応' : '参考',
                $i['type'], $i['code'], $i['count'], $i['first_date'], $i['last_date'],
                implode(' ', $i['related']), implode(' ', $i['examples']), $i['hint'],
            ]);
        }
        foreach ($result['errors'] as $e) {
            $put(['要対応', '読み取りエラー', '行 ' . $e['line'], 1, '', '', '', '', $e['reason']]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);
        return $csv;
    }
}
