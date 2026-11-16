<?php

namespace App\Services\ArrivalFlights\Legacy;

use RuntimeException;

/**
 * 現行システムの CSV（flt_corp_tbl / flt_dpt_tbl / flt_corp_term_tbl / flt_tbl）を読み込む。
 * UTF-8（BOM あり・なし）と Shift_JIS に対応。Laravel に依存しない。
 */
class LegacyCsvReader
{
    public const FILES = [
        'corp' => ['label' => '航空会社（flt_corp_tbl）', 'columns' => ['flt_corp_id', 'name', 'name_k', 'del_flg', 'japan_flg', 'memo']],
        'dpt' => ['label' => '出発空港（flt_dpt_tbl）', 'columns' => ['flt_dpt_id', 'name', 'name_k', 'del_flg']],
        'term' => ['label' => 'ターミナル（flt_corp_term_tbl）', 'columns' => ['air_port_id', 'flt_corp_id', 'term_id', 'default_flg', 'del_flg']],
        'flt' => ['label' => '到着便（flt_tbl）', 'columns' => ['air_port_id', 'flt_corp_id', 'flt_id', 'flt_dpt_id', 'arv_date', 'arv_time', 'term_id', 'del_flg']],
    ];

    /**
     * @param string $kind corp / dpt / term / flt
     * @return array<int, array<string,string>> 列名 => 値（前後の空白は除く）
     */
    public function read(string $kind, string $path): array
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException(self::FILES[$kind]['label'] . 'を読み込めませんでした。');
        }
        return $this->readString($kind, $content);
    }

    public function readString(string $kind, string $content): array
    {
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        } elseif (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'SJIS-win');
        }

        $h = fopen('php://temp', 'r+');
        fwrite($h, $content);
        rewind($h);

        $header = fgetcsv($h, 0, ',', '"', '');
        if (!$header) {
            throw new RuntimeException(self::FILES[$kind]['label'] . 'が空です。');
        }
        $header = array_map(fn ($v) => trim((string) $v), $header);
        $missing = array_diff(self::FILES[$kind]['columns'], $header);
        if ($missing) {
            throw new RuntimeException(self::FILES[$kind]['label'] . 'に次の列がありません: ' . implode(', ', $missing)
                . '（別のファイルを選んでいないか確認してください）');
        }

        $rows = [];
        while (($row = fgetcsv($h, 0, ',', '"', '')) !== false) {
            if ($row === [null] || trim(implode('', $row)) === '') {
                continue;
            }
            $rec = [];
            foreach ($header as $i => $col) {
                $rec[$col] = trim((string) ($row[$i] ?? ''));
            }
            $rows[] = $rec;
        }
        fclose($h);

        return $rows;
    }
}
