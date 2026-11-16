<?php

namespace App\Console\Commands;

use App\Models\Airport;
use App\Services\ArrivalFlights\ArrivalFlightImportService;
use App\Services\ArrivalFlights\ArrivalFlightMasterSqlBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportArrivalFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-arrival-flights
                            {file : Sun_Parking_YYYY-MM-DD.csv のパス}
                            {--dry-run : DBを変更せず、件数と確認リストだけ表示する}
                            {--force : マスタにないもの（変換できないもの）があっても NULL で取り込む}
                            {--issues= : 確認リストを書き出すCSVのパス}
                            {--master-sql= : マスタにないものがあれば、登録用SQLを書き出すパス}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sun_Parking CSV から到着便を取り込む（あれば更新・なければ挿入。削除はしない）';

    /**
     * Execute the console command.
     */
    public function handle(ArrivalFlightImportService $service)
    {
        $file = $this->argument('file');
        if (!is_file($file)) {
            $this->error("ファイルが見つかりません: {$file}");
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $this->info(($dryRun ? '[確認のみ] ' : '') . "到着便を取り込みます: {$file}");

        try {
            $result = $service->import($file, myOffice()->airport_id, $dryRun, (bool) $this->option('force'));
        } catch (\Throwable $e) {
            $this->error('取り込みに失敗しました: ' . $e->getMessage());
            Log::error('到着便CSVの取り込みに失敗しました。', ['file' => $file, 'exception' => $e]);
            return self::FAILURE;
        }

        $this->table(['CSVの行数', '対象の便', '新規', '更新', '変更なし'], [[
            $result['total'], $result['target'], $result['inserted'], $result['updated'], $result['unchanged'],
        ]]);

        $blocking = array_filter($result['issues'], fn ($i) => $i['blocking']);
        if ($blocking || $result['errors']) {
            $this->warn($result['status'] === 'blocked'
                ? 'マスタにない（変換できない）ものがあるため、取り込みを中止しました。データは変更していません。'
                : 'マスタにない（変換できない）もの');
            foreach (collect($blocking)->groupBy('type') as $type => $items) {
                $this->line("■ {$type}（{$items->count()} 件）");
                $this->table(['コード', '便数', '到着日', '関連', '例', 'やること'], $items->map(fn ($i) => [
                    $i['code'], $i['count'], "{$i['first_date']} 〜 {$i['last_date']}",
                    implode(' ', $i['related']), implode('、', $i['examples']), $i['hint'],
                ])->all());
            }
            foreach ($result['errors'] as $e) {
                $this->line("  行 {$e['line']}: {$e['reason']}");
            }
            if ($result['status'] === 'blocked') {
                $this->line('マスタを登録してから再実行するか、NULL のまま取り込む場合は --force を付けてください。');
            }
        }

        $info = array_filter($result['issues'], fn ($i) => !$i['blocking']);
        if ($info) {
            $this->line('参考（対応不要）');
            foreach ($info as $i) {
                $this->line("  {$i['type']}: {$i['code']}（{$i['count']} 便）");
            }
        }

        if ($path = $this->option('issues')) {
            file_put_contents($path, ArrivalFlightImportService::issuesToCsv($result));
            $this->info("確認リストを書き出しました: {$path}");
        }

        if ($path = $this->option('master-sql')) {
            $arrCode = Airport::withTrashed()->find(myOffice()->airport_id)?->code ?? 'NRT';
            $sql = (new ArrivalFlightMasterSqlBuilder())->build($result['issues'], $arrCode, basename($file), now()->format('Y-m-d H:i'));
            if ($sql === null) {
                $this->info('マスタに登録が必要なものはないため、SQLは書き出していません。');
            } else {
                file_put_contents($path, $sql);
                $this->info("マスタ登録用SQLを書き出しました: {$path}（【要入力】を書き換えてから実行してください）");
            }
        }

        return $result['status'] === 'blocked' ? self::FAILURE : self::SUCCESS;
    }
}
