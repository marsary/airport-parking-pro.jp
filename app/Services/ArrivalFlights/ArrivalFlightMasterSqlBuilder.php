<?php

namespace App\Services\ArrivalFlights;

/**
 * 到着便の取り込みで「マスタにない」と判定されたものから、マスタ登録用の SQL を作る。
 *
 * - airlines / airports：コードは入れ、名前・ふりがなは【要入力】にする（CSV に名前がないため）
 * - airport_terminals：CSV に出てきたターミナル番号で登録する。CSV がターミナル0だけの航空会社は、
 *   番号が分からないのでコメントにして残す
 * - id は書かず、コードからサブクエリで引く（AUTO_INCREMENT の値に左右されない）
 *
 * Laravel に依存しない純粋なクラス。
 */
class ArrivalFlightMasterSqlBuilder
{
    public const PLACEHOLDER = '【要入力】';

    /**
     * @param array  $issues          ArrivalFlightImportPlanner::plan()['issues']
     * @param string $arrAirportCode  到着空港のコード（例: NRT）
     * @param string $fileName        元の CSV ファイル名（コメント用）
     * @param string $createdAt       作成日時（コメント用）
     */
    public function build(array $issues, string $arrAirportCode, string $fileName, string $createdAt): ?string
    {
        $airlines = [];          // code => issue
        $airports = [];          // code => issue
        $terminals = [];         // "code|no" => [carrier, no, count, note]
        $terminalUnknown = [];   // carrier => [count, examples, note]

        foreach ($issues as $i) {
            $meta = $i['meta'] ?? [];
            switch ($i['type']) {
                case ArrivalFlightImportPlanner::ISSUE_AIRLINE:
                    $airlines[$meta['carrier']] = $i;
                    $counts = $i['terminal_counts'] ?? [];
                    if ($counts) {
                        foreach ($counts as $no => $count) {
                            $terminals[$meta['carrier'] . '|' . $no] = [$meta['carrier'], (int) $no, $count, '新規の航空会社'];
                        }
                    } else {
                        $terminalUnknown[$meta['carrier']] = [$i['count'], $i['examples'], 'CSVのターミナルがすべて0のため番号が不明'];
                    }
                    break;
                case ArrivalFlightImportPlanner::ISSUE_AIRPORT:
                    $airports[$meta['dep_code']] = $i;
                    break;
                case ArrivalFlightImportPlanner::ISSUE_TERMINAL:
                    $terminals[$meta['carrier'] . '|' . $meta['terminal_no']] = [$meta['carrier'], (int) $meta['terminal_no'], $i['count'], ''];
                    break;
                case ArrivalFlightImportPlanner::ISSUE_TERMINAL_NONE:
                    $terminalUnknown[$meta['carrier']] = [$i['count'], $i['examples'], 'CSVのターミナルがすべて0のため番号が不明'];
                    break;
            }
        }

        if (!$airlines && !$airports && !$terminals && !$terminalUnknown) {
            return null;
        }

        ksort($airlines);
        ksort($airports);
        uasort($terminals, fn ($a, $b) => [$a[0], -$a[2], $a[1]] <=> [$b[0], -$b[2], $b[1]]); // 航空会社ごとに便数の多い順
        ksort($terminalUnknown);

        $P = self::PLACEHOLDER;
        $L = [];
        $L[] = '-- 到着便の取り込みに必要なマスタの登録SQL';
        $L[] = "-- 元のCSV: {$fileName}  /  作成: {$createdAt}  /  到着空港: {$arrAirportCode}";
        $L[] = '--';
        $L[] = "-- ※ {$P} の箇所を正しい値に書き換えてから実行してください。";
        $L[] = '--    CSVには航空会社名・空港名がないため、コード以外は入力が必要です。';
        $L[] = '-- ※ 実行後、同じCSVをもう一度アップロードしてください。';
        $L[] = '';
        $L[] = 'SET NAMES utf8mb4;';
        $L[] = 'SET @arr_airport_id = (SELECT id FROM airports WHERE code = ' . $this->q($arrAirportCode) . ' AND deleted_at IS NULL ORDER BY id LIMIT 1);';
        $L[] = '';
        $L[] = 'START TRANSACTION;';

        if ($airlines) {
            $L[] = '';
            $L[] = '-- 1) airlines（' . count($airlines) . ' 件）';
            $L[] = '--    japan_flg: 日本の航空会社なら 1';
            foreach ($airlines as $code => $i) {
                $L[] = sprintf('-- %s：%d 便（%s〜%s）例 %s', $code, $i['count'], $i['first_date'], $i['last_date'], implode('、', $i['examples']));
                $L[] = 'INSERT INTO `airlines` (`code`, `name`, `kana`, `search_keyword`, `sort`, `memo`, `japan_flg`, `created_at`, `updated_at`, `deleted_at`)';
                $L[] = '  VALUES (' . $this->q($code) . ", '{$P}航空会社名', '{$P}ふりがな', NULL, NULL, NULL, 0, NOW(), NOW(), NULL);";
            }
        }

        if ($airports) {
            $L[] = '';
            $L[] = '-- 2) airports（' . count($airports) . ' 件）';
            $L[] = '--    name は都市名（例: アルマトイ）、name_full は英語の空港名、location は国名';
            foreach ($airports as $code => $i) {
                $L[] = sprintf('-- %s：%d 便（%s〜%s）航空会社 %s', $code, $i['count'], $i['first_date'], $i['last_date'], implode(' ', $i['related']));
                $L[] = 'INSERT INTO `airports` (`code`, `name`, `name_kana`, `name_full`, `location`, `created_at`, `updated_at`, `deleted_at`)';
                $L[] = '  VALUES (' . $this->q($code) . ", '{$P}空港名', '{$P}ふりがな', '{$P}英語の空港名', '{$P}国名', NOW(), NOW(), NULL);";
            }
        }

        if ($terminals || $terminalUnknown) {
            $L[] = '';
            $L[] = "-- 3) airport_terminals（到着空港 {$arrAirportCode} のターミナル）";
            $L[] = '--    その航空会社の最初のターミナルを default_flg = 1（ターミナル0の便の補完に使う）にします';
            foreach ($terminals as [$carrier, $no, $count, $note]) {
                $L[] = sprintf('-- %s 第%dターミナル：%d 便%s', $carrier, $no, $count, $note ? "（{$note}）" : '');
                $L[] = $this->terminalInsert($carrier, (string) $no);
            }
            foreach ($terminalUnknown as $carrier => [$count, $examples, $note]) {
                $L[] = sprintf('-- %s：%d 便（%s）例 %s', $carrier, $count, $note, implode('、', $examples));
                $L[] = '-- ターミナル番号（1〜3）を入れて、行頭の「-- 」を外してから実行してください。';
                foreach (explode("\n", $this->terminalInsert($carrier, $P . 'ターミナル番号')) as $line) {
                    $L[] = '-- ' . $line;
                }
            }
        }

        $L[] = '';
        $L[] = 'COMMIT;';
        $L[] = '';
        $L[] = "-- 確認: {$P} が残っている行（0件なら OK）";
        $L[] = "SELECT 'airlines' AS `テーブル`, code, name FROM airlines WHERE name LIKE '%{$P}%' OR kana LIKE '%{$P}%'";
        $L[] = 'UNION ALL';
        $L[] = "SELECT 'airports', code, name FROM airports WHERE name LIKE '%{$P}%' OR name_kana LIKE '%{$P}%' OR name_full LIKE '%{$P}%' OR location LIKE '%{$P}%';";

        return implode("\n", $L) . "\n";
    }

    private function terminalInsert(string $carrier, string $terminalNo): string
    {
        $no = ctype_digit($terminalNo) ? $terminalNo : "'{$terminalNo}'";
        $c = $this->q($carrier);
        return "INSERT INTO `airport_terminals` (`airport_id`, `airline_id`, `terminal_id`, `default_flg`, `created_at`, `updated_at`, `deleted_at`)\n"
            . "  SELECT @arr_airport_id, a.id, {$no},\n"
            . "         IF(EXISTS (SELECT 1 FROM airport_terminals t WHERE t.airport_id = @arr_airport_id AND t.airline_id = a.id AND t.deleted_at IS NULL), 0, 1),\n"
            . "         NOW(), NOW(), NULL\n"
            . "    FROM airlines a WHERE a.code = {$c} AND a.deleted_at IS NULL ORDER BY a.id LIMIT 1;";
    }

    private function q(string $v): string
    {
        return "'" . str_replace(["\\", "'"], ["\\\\", "''"], $v) . "'";
    }
}
