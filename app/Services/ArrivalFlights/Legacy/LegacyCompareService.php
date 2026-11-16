<?php

namespace App\Services\ArrivalFlights\Legacy;

use App\Models\Airport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * 現行システムの CSV と今のDBを比較し、結果と SQL を保存する。
 * 画面（LegacyCompareController）と Artisan コマンド（app:compare-legacy-flights）から使う。
 */
class LegacyCompareService
{
    /** 結果とSQLの保存先（storage/app/legacy_compare） */
    public const STORE_DIR = 'legacy_compare';

    /** 画面に出す一覧の上限（それ以上は件数だけ。全件は SQL のコメントにある） */
    public const LIST_LIMIT = 200;

    public function __construct(
        private LegacyCsvReader $reader = new LegacyCsvReader(),
        private LegacyComparator $comparator = new LegacyComparator(),
        private LegacySqlBuilder $builder = new LegacySqlBuilder(),
    ) {
    }

    /**
     * @param array<string,string> $paths     corp / dpt / term / flt => CSV のパス
     * @param array<string,string> $fileNames corp / dpt / term / flt => 元のファイル名
     * @return array 画面表示用の結果（一覧は LIST_LIMIT 件まで）。'sql_path' に SQL の保存先（変更なしなら null）
     */
    public function run(array $paths, array $fileNames, int $arrAirportId): array
    {
        if ($this->memoryLimitBytes() < 1024 * 1024 * 1024) {
            @ini_set('memory_limit', '1024M');
        }

        $arrCode = Airport::withTrashed()->find($arrAirportId)?->code;
        if ($arrCode !== 'NRT') {
            throw new RuntimeException("店舗の空港が成田（NRT）ではありません（現在: " . ($arrCode ?? "id {$arrAirportId}") . "）。"
                . '現行データとの比較は成田だけが対象です。店舗（offices）の airport_id を確認してください。');
        }

        $legacy = [];
        foreach (array_keys(LegacyCsvReader::FILES) as $kind) {
            $legacy[$kind] = $this->reader->read($kind, $paths[$kind]);
        }

        $result = $this->comparator->compare($legacy, $this->snapshot($arrAirportId));

        $labels = [];
        foreach (LegacyCsvReader::FILES as $kind => $def) {
            $labels[$def['label']] = $fileNames[$kind] ?? basename($paths[$kind]);
        }
        $createdAt = now()->format('Y-m-d H:i');
        $sql = $this->builder->build($result, $arrCode, $labels, $createdAt);

        $sqlPath = null;
        if ($sql !== null) {
            $sqlPath = self::STORE_DIR . '/' . now()->format('Ymd_His') . '_現行データ反映.sql';
            Storage::disk('local')->put($sqlPath, $sql);
        }

        return $this->forView($result) + ['sql_path' => $sqlPath, 'created_at' => $createdAt, 'files' => $labels];
    }

    /** 今のDBの状態を、LegacyComparator が受け取る形にする */
    public function snapshot(int $arrAirportId): array
    {
        $airlines = [];
        foreach (DB::table('airlines')->orderBy('id')->get(['id', 'code', 'name', 'kana', 'japan_flg', 'memo', 'deleted_at']) as $a) {
            // 同じコードが複数あれば、削除されていないもののうち id が小さいもの
            if (!isset($airlines[$a->code]) || ($airlines[$a->code]['deleted'] && $a->deleted_at === null)) {
                $airlines[$a->code] = ['id' => $a->id, 'name' => $a->name, 'kana' => $a->kana, 'japan_flg' => (int) $a->japan_flg, 'memo' => $a->memo, 'deleted' => $a->deleted_at !== null];
            }
        }
        $airports = [];
        foreach (DB::table('airports')->orderBy('id')->get(['id', 'code', 'name', 'name_kana', 'deleted_at']) as $a) {
            if (!isset($airports[$a->code]) || ($airports[$a->code]['deleted'] && $a->deleted_at === null)) {
                $airports[$a->code] = ['id' => $a->id, 'name' => $a->name, 'name_kana' => $a->name_kana, 'deleted' => $a->deleted_at !== null];
            }
        }
        $terminals = DB::table('airport_terminals as t')
            ->join('airlines as a', 'a.id', '=', 't.airline_id')
            ->where('t.airport_id', $arrAirportId)->whereNull('t.deleted_at')
            ->orderBy('t.id')
            ->get(['t.id', 'a.code as airline', 't.terminal_id as terminal_no', 't.default_flg'])
            ->map(fn ($t) => ['id' => $t->id, 'airline' => $t->airline, 'terminal_no' => (int) $t->terminal_no, 'default_flg' => (int) $t->default_flg])
            ->all();

        $flights = [];
        DB::table('arrival_flights as f')
            ->leftJoin('airports as dep', 'dep.id', '=', 'f.dep_airport_id')
            ->leftJoin('airport_terminals as t', 't.id', '=', 'f.terminal_id')
            ->leftJoin('airlines as a', 'a.id', '=', 'f.airline_id')
            ->where('f.arr_airport_id', $arrAirportId)
            ->select(['f.id', 'f.name', 'f.flight_no', 'f.arrive_date', 'dep.code as dep', 'f.arrive_time', 't.terminal_id as terminal_no', 'a.code as airline', 'f.deleted_at'])
            ->orderBy('f.id')
            ->chunk(5000, function ($rows) use (&$flights) {
                foreach ($rows as $f) {
                    $flights[] = [
                        'id' => $f->id, 'name' => (string) $f->name, 'flight_no' => $f->flight_no,
                        'arrive_date' => substr((string) $f->arrive_date, 0, 10), 'dep' => $f->dep,
                        'arrive_time' => $f->arrive_time ? substr((string) $f->arrive_time, 0, 8) : null,
                        'terminal_no' => $f->terminal_no === null ? null : (int) $f->terminal_no,
                        'airline' => $f->airline, 'deleted' => $f->deleted_at !== null,
                    ];
                }
            });

        return compact('airlines', 'airports', 'terminals', 'flights');
    }

    /** 画面・セッション用に一覧を絞る（件数は残す） */
    private function forView(array $r): array
    {
        $cut = fn (array $list) => ['count' => count($list), 'items' => array_slice($list, 0, self::LIST_LIMIT)];
        unset($r['terminals']['after']);
        foreach (['airlines', 'airports'] as $k) {
            foreach (['insert', 'update', 'dbOnly'] as $c) {
                $r[$k][$c] = $cut($r[$k][$c]);
            }
        }
        foreach (['insert', 'default', 'dbOnly'] as $c) {
            $r['terminals'][$c] = $cut($r['terminals'][$c]);
        }
        foreach (['insert', 'update', 'dbOnly'] as $c) {
            $r['flights'][$c] = $cut($r['flights'][$c]);
        }
        return $r;
    }

    private function memoryLimitBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));
        if ($limit === '-1') {
            return PHP_INT_MAX;
        }
        $num = (int) $limit;
        return match (strtolower(substr($limit, -1))) {
            'g' => $num * 1024 ** 3,
            'm' => $num * 1024 ** 2,
            'k' => $num * 1024,
            default => $num,
        };
    }
}
