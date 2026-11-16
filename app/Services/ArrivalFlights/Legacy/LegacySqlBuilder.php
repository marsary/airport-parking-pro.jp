<?php

namespace App\Services\ArrivalFlights\Legacy;

/**
 * LegacyComparator の結果から、修正・新規作成の SQL を作る。
 *
 * - 削除の SQL は作らない
 * - 航空会社・空港・ターミナルの id はコードから変数（@al_JL など）に引いて使う（環境ごとの id の違いに左右されない）
 * - 到着便の更新は id ＋ 便名 ＋ 到着日で対象を絞る（別の環境で流しても違う行を書き換えない）
 * - 全体を1つのトランザクションにする
 *
 * Laravel に依存しない。
 */
class LegacySqlBuilder
{
    public function build(array $r, string $arrAirportCode, array $fileNames, string $createdAt): ?string
    {
        if (!$r['has_changes']) {
            return null;
        }
        $L = [];
        $L[] = '-- 現行システムのデータに合わせる修正・新規作成SQL（到着空港: ' . $arrAirportCode . '）';
        $L[] = '-- 作成: ' . $createdAt;
        foreach ($fileNames as $label => $name) {
            $L[] = "--   {$label}: {$name}";
        }
        $L[] = '-- 削除は行いません。現行の削除フラグ（del_flg）は反映していません。';
        $L[] = '';
        $L[] = 'SET NAMES utf8mb4;';
        $L[] = 'SET @arr = (SELECT id FROM airports WHERE code = ' . $this->q($arrAirportCode) . ' AND deleted_at IS NULL ORDER BY id LIMIT 1);';
        $L[] = '';
        $L[] = 'START TRANSACTION;';

        // 1) 航空会社
        $a = $r['airlines'];
        if ($a['insert'] || $a['update']) {
            $L[] = '';
            $L[] = '-- 1) airlines：追加 ' . count($a['insert']) . ' 件・変更 ' . count($a['update']) . ' 件';
            foreach ($a['insert'] as $x) {
                $L[] = 'INSERT INTO `airlines` (`code`, `name`, `kana`, `search_keyword`, `sort`, `memo`, `japan_flg`, `created_at`, `updated_at`, `deleted_at`) VALUES ('
                    . implode(', ', [$this->q($x['code']), $this->q($x['name']), $this->q($x['kana']), 'NULL', 'NULL', $this->q($x['memo']), (int) $x['japan_flg'], 'NOW()', 'NOW()', 'NULL']) . ');';
            }
            foreach ($a['update'] as $x) {
                $L[] = '-- ' . $x['code'] . '：' . $this->describe($x['changes']);
                $L[] = 'UPDATE `airlines` SET ' . $this->sets($x['changes'], ['japan_flg' => 'int']) . ', `updated_at` = NOW() WHERE `code` = ' . $this->q($x['code']) . ' AND `deleted_at` IS NULL;';
            }
        }

        // 2) 出発空港
        $p = $r['airports'];
        if ($p['insert'] || $p['update']) {
            $L[] = '';
            $L[] = '-- 2) airports：追加 ' . count($p['insert']) . ' 件・変更 ' . count($p['update']) . ' 件（name_full・location は現行にないため空のまま）';
            foreach ($p['insert'] as $x) {
                $L[] = 'INSERT INTO `airports` (`code`, `name`, `name_kana`, `name_full`, `location`, `created_at`, `updated_at`, `deleted_at`) VALUES ('
                    . implode(', ', [$this->q($x['code']), $this->q($x['name']), $this->q($x['name_kana']), 'NULL', 'NULL', 'NOW()', 'NOW()', 'NULL']) . ');';
            }
            foreach ($p['update'] as $x) {
                $L[] = '-- ' . $x['code'] . '：' . $this->describe($x['changes']);
                $L[] = 'UPDATE `airports` SET ' . $this->sets($x['changes']) . ', `updated_at` = NOW() WHERE `code` = ' . $this->q($x['code']) . ' AND `deleted_at` IS NULL;';
            }
        }

        // 使うコードの id を変数に
        $t = $r['terminals'];
        $f = $r['flights'];
        $airlineCodes = array_unique(array_merge(
            array_column($t['insert'], 'code'), array_column($t['default'], 'code'),
            array_column($f['insert'], 'carrier'),
            array_column(array_filter($f['update'], fn ($u) => isset($u['changes']['airline']) || isset($u['changes']['terminal'])), 'carrier'),
        ));
        $airportCodes = array_unique(array_filter(array_merge(
            array_column($f['insert'], 'dep'),
            array_map(fn ($u) => $u['changes']['dep'][1] ?? null, $f['update']),
        )));
        sort($airlineCodes);
        sort($airportCodes);
        if ($airlineCodes || $airportCodes) {
            $L[] = '';
            $L[] = '-- id をコードから引く';
            foreach ($airlineCodes as $c) {
                $L[] = 'SET ' . $this->alVar($c) . ' = (SELECT id FROM airlines WHERE code = ' . $this->q($c) . ' AND deleted_at IS NULL ORDER BY id LIMIT 1);';
            }
            foreach ($airportCodes as $c) {
                $L[] = 'SET ' . $this->apVar($c) . ' = (SELECT id FROM airports WHERE code = ' . $this->q($c) . ' AND deleted_at IS NULL ORDER BY id LIMIT 1);';
            }
        }

        // 3) ターミナル
        if ($t['insert'] || $t['default']) {
            $L[] = '';
            $L[] = '-- 3) airport_terminals：追加 ' . count($t['insert']) . ' 件・既定の変更 ' . count($t['default']) . ' 件';
            foreach ($t['default'] as $x) {
                $L[] = "-- {$x['code']}：既定ターミナルを第{$x['terminal_no']}にする（現行の既定）";
                $L[] = 'UPDATE `airport_terminals` SET `default_flg` = 0, `updated_at` = NOW() WHERE `airport_id` = @arr AND `airline_id` = ' . $this->alVar($x['code']) . ' AND `terminal_id` <> ' . (int) $x['terminal_no'] . ' AND `deleted_at` IS NULL;';
                if ($x['reason'] === 'switch') {
                    $L[] = 'UPDATE `airport_terminals` SET `default_flg` = 1, `updated_at` = NOW() WHERE `airport_id` = @arr AND `airline_id` = ' . $this->alVar($x['code']) . ' AND `terminal_id` = ' . (int) $x['terminal_no'] . ' AND `deleted_at` IS NULL;';
                }
            }
            foreach ($t['insert'] as $x) {
                if ($x['note']) {
                    $L[] = "-- {$x['code']} 第{$x['terminal_no']}（{$x['note']}）";
                }
                $L[] = 'INSERT INTO `airport_terminals` (`airport_id`, `airline_id`, `terminal_id`, `default_flg`, `created_at`, `updated_at`, `deleted_at`) VALUES (@arr, '
                    . $this->alVar($x['code']) . ', ' . (int) $x['terminal_no'] . ', ' . (int) $x['default_flg'] . ', NOW(), NOW(), NULL);';
            }
        }

        // ターミナルの id を変数に
        $termKeys = [];
        foreach ($f['insert'] as $x) {
            if ($x['terminal_no'] !== null) {
                $termKeys[$x['carrier'] . '|' . $x['terminal_no']] = true;
            }
        }
        foreach ($f['update'] as $x) {
            if (isset($x['changes']['terminal'])) {
                $termKeys[$x['carrier'] . '|' . $x['changes']['terminal'][1]] = true;
            }
        }
        ksort($termKeys);
        if ($termKeys) {
            $L[] = '';
            $L[] = '-- ターミナルの id を引く';
            foreach (array_keys($termKeys) as $k) {
                [$c, $no] = explode('|', $k);
                $L[] = 'SET ' . $this->tVar($c, (int) $no) . ' = (SELECT id FROM airport_terminals WHERE airport_id = @arr AND airline_id = ' . $this->alVar($c)
                    . ' AND terminal_id = ' . (int) $no . ' AND deleted_at IS NULL ORDER BY default_flg DESC, id LIMIT 1);';
            }
        }

        // 4) 到着便
        if ($f['update']) {
            $L[] = '';
            $L[] = '-- 4) arrival_flights の変更：' . count($f['update']) . ' 件';
            foreach ($f['update'] as $x) {
                $sets = [];
                foreach ($x['changes'] as $col => [$old, $new]) {
                    $sets[] = match ($col) {
                        'dep' => '`dep_airport_id` = ' . ($new === null ? 'NULL' : $this->apVar($new)),
                        'arrive_time' => '`arrive_time` = ' . $this->q($new),
                        'terminal' => '`terminal_id` = ' . $this->tVar($x['carrier'], (int) $new),
                        'airline' => '`airline_id` = ' . $this->alVar($x['carrier']),
                    };
                }
                $L[] = "-- {$x['name']} {$x['date']}：" . $this->describe($x['changes']);
                $L[] = 'UPDATE `arrival_flights` SET ' . implode(', ', $sets) . ', `updated_at` = NOW() WHERE `id` = ' . (int) $x['id']
                    . ' AND `name` = ' . $this->q($x['name']) . ' AND `arrive_date` = ' . $this->q($x['date']) . ';';
            }
        }
        if ($f['insert']) {
            $L[] = '';
            $L[] = '-- 5) arrival_flights の追加：' . count($f['insert']) . ' 件';
            foreach ($f['insert'] as $x) {
                $L[] = 'INSERT INTO `arrival_flights` (`flight_no`, `name`, `dep_airport_id`, `arr_airport_id`, `airline_id`, `terminal_id`, `arrive_date`, `arrive_time`, `codeshare`, `created_at`, `updated_at`, `deleted_at`) VALUES ('
                    . implode(', ', [
                        $this->q($x['flight_no']), $this->q($x['name']),
                        $x['dep'] === null ? 'NULL' : $this->apVar($x['dep']), '@arr', $this->alVar($x['carrier']),
                        $x['terminal_no'] === null ? 'NULL' : $this->tVar($x['carrier'], (int) $x['terminal_no']),
                        $this->q($x['date']), $this->q($x['time']), 'NULL', 'NOW()', 'NOW()', 'NULL',
                    ]) . ');';
            }
        }

        $L[] = '';
        $L[] = 'COMMIT;';
        return implode("\n", $L) . "\n";
    }

    private function describe(array $changes): string
    {
        $label = ['name' => '名前', 'kana' => 'ふりがな', 'name_kana' => 'ふりがな', 'japan_flg' => 'japan_flg', 'memo' => 'memo',
            'dep' => '出発地', 'arrive_time' => '時刻', 'terminal' => 'ターミナル', 'airline' => '航空会社'];
        $out = [];
        foreach ($changes as $col => [$old, $new]) {
            $fmt = fn ($v) => $v === null ? '(空)' : ($col === 'terminal' ? '第' . $v : ($col === 'arrive_time' ? substr($v, 0, 5) : $v));
            $out[] = ($label[$col] ?? $col) . ' ' . $fmt($old) . ' → ' . $fmt($new);
        }
        return str_replace(["\r", "\n"], ' ', implode('、', $out));
    }

    private function sets(array $changes, array $types = []): string
    {
        $out = [];
        foreach ($changes as $col => [, $new]) {
            $out[] = "`{$col}` = " . (($types[$col] ?? '') === 'int' ? (int) $new : $this->q($new));
        }
        return implode(', ', $out);
    }

    private function alVar(string $code): string
    {
        return '@al_' . preg_replace('/[^A-Za-z0-9]/', '_', $code);
    }

    private function apVar(string $code): string
    {
        return '@ap_' . preg_replace('/[^A-Za-z0-9]/', '_', $code);
    }

    private function tVar(string $code, int $no): string
    {
        return '@t_' . preg_replace('/[^A-Za-z0-9]/', '_', $code) . '_' . $no;
    }

    private function q(?string $v): string
    {
        return $v === null ? 'NULL' : "'" . str_replace(['\\', "'"], ['\\\\', "''"], $v) . "'";
    }
}
