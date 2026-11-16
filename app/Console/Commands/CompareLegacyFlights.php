<?php

namespace App\Console\Commands;

use App\Services\ArrivalFlights\Legacy\LegacyCompareService;
use App\Services\ArrivalFlights\Legacy\LegacyCsvReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompareLegacyFlights extends Command
{
    protected $signature = 'app:compare-legacy-flights
                            {dir : 現行システムのCSV（public.flt_corp_tbl.csv など4ファイル）を置いたフォルダ}
                            {--sql= : SQLを書き出すパス（省略時は storage/app/legacy_compare に保存）}';

    protected $description = '現行システムのデータ（成田）と比較し、修正・新規作成のSQLを作る（DBは変更しない）';

    /** フォルダ内のファイル名の探し方 */
    private const PATTERNS = [
        'corp' => '*flt_corp_tbl*.csv',
        'dpt' => '*flt_dpt_tbl*.csv',
        'term' => '*flt_corp_term_tbl*.csv',
        'flt' => '*flt_tbl*.csv',
    ];

    public function handle(LegacyCompareService $service)
    {
        $dir = rtrim($this->argument('dir'), '/\\');
        $paths = [];
        foreach (self::PATTERNS as $kind => $pattern) {
            $found = array_values(array_filter(glob($dir . DIRECTORY_SEPARATOR . $pattern) ?: [], function ($p) use ($kind) {
                // flt_tbl のパターンは flt_corp_tbl などにも当たるので除く
                return $kind !== 'flt' || !preg_match('/flt_(corp|dpt|corp_term)_tbl/', basename($p));
            }));
            if (count($found) !== 1) {
                $this->error(LegacyCsvReader::FILES[$kind]['label'] . ' のファイルが ' . (count($found) ? '複数' : '見つかりません') . "（{$pattern}）");
                return self::FAILURE;
            }
            $paths[$kind] = $found[0];
        }

        try {
            $r = $service->run($paths, array_map('basename', $paths), myOffice()->airport_id);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $f = $r['flights'];
        $this->table(['対象', '追加', '変更', 'DBにだけある（削除しない）'], [
            ['航空会社', $r['airlines']['insert']['count'], $r['airlines']['update']['count'], $r['airlines']['dbOnly']['count']],
            ['出発空港', $r['airports']['insert']['count'], $r['airports']['update']['count'], $r['airports']['dbOnly']['count']],
            ['成田のターミナル', $r['terminals']['insert']['count'], $r['terminals']['default']['count'], $r['terminals']['dbOnly']['count']],
            ['成田の到着便', $f['insert']['count'], $f['update']['count'], $f['dbOnly']['count']],
        ]);
        if ($f['range']) {
            $this->line("到着便の期間 {$f['range'][0]}〜{$f['range'][1]}、変更なし {$f['unchanged']} 件。羽田は対象外（ターミナル {$r['terminals']['hndCount']} 件・到着便 {$f['hndCount']} 件）。");
        }
        foreach ($f['missing'] as $what => $n) {
            $this->warn("マスタがないため NULL：{$what}（{$n} 便）");
        }
        if ($r['messages']) {
            $this->line('お知らせ');
            foreach ($r['messages'] as $m) {
                $this->line("  [{$m['type']}] {$m['code']}：{$m['text']}");
            }
        }

        if (!$r['sql_path']) {
            $this->info('現行データとの差異はありません。');
            return self::SUCCESS;
        }
        if ($out = $this->option('sql')) {
            file_put_contents($out, Storage::disk('local')->get($r['sql_path']));
            $this->info("SQLを書き出しました: {$out}");
        } else {
            $this->info('SQLを保存しました: ' . Storage::disk('local')->path($r['sql_path']));
        }
        return self::SUCCESS;
    }
}
