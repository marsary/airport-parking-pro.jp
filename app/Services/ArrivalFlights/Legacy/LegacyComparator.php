<?php

namespace App\Services\ArrivalFlights\Legacy;

/**
 * 現行システムのデータ（正）と今のDBを突き合わせ、差異と修正・新規作成SQLを作る。
 *
 * 方針
 * - ID ではなくコードで照合する（航空会社コード・空港コード・便名＋到着日＋出発地）
 * - 削除はしない。DBにだけあるものは一覧に出すだけ
 * - 現行の del_flg は SQL に反映しない（DBを優先）。削除済みのものはメッセージに出す
 * - 対象は成田（現行の air_port_id = 1）だけ。羽田（2）は件数だけ数える
 * - 現行のターミナル番号は 1→第1、2→第2、6→第3。0（不明）は航空会社の既定ターミナルで補う
 *
 * Laravel に依存しない純粋なクラス。
 */
class LegacyComparator
{
    /** 現行の成田のターミナル番号 → 今のシステムのターミナル番号 */
    public const NRT_TERM_MAP = ['1' => 1, '2' => 2, '6' => 3];
    public const LEGACY_NRT = '1';
    public const LEGACY_HND = '2';

    private array $msg = [];

    /**
     * @param array $legacy ['corp' => [...], 'dpt' => [...], 'term' => [...], 'flt' => [...]]（LegacyCsvReader の結果）
     * @param array $db [
     *   'airlines'  => [code => ['id','name','kana','japan_flg','memo','deleted']],
     *   'airports'  => [code => ['id','name','name_kana','deleted']],
     *   'terminals' => [['id','airline','terminal_no','default_flg']]   // 成田・削除されていないもの
     *   'flights'   => [['id','name','flight_no','arrive_date','dep','arrive_time','terminal_no','airline','deleted']] // 成田
     * ]
     */
    public function compare(array $legacy, array $db): array
    {
        $this->msg = [];

        $airlines = $this->compareAirlines($legacy['corp'], $db['airlines']);
        $airports = $this->compareAirports($legacy['dpt'], $db['airports']);

        // この後の判定に使う「SQL実行後に存在するコード」
        $airlineCodes = array_fill_keys(array_merge(
            array_keys(array_filter($db['airlines'], fn ($a) => !$a['deleted'])),
            array_column($airlines['insert'], 'code'),
        ), true);
        $airportCodes = array_fill_keys(array_merge(
            array_keys(array_filter($db['airports'], fn ($a) => !$a['deleted'])),
            array_column($airports['insert'], 'code'),
        ), true);

        $terminals = $this->compareTerminals($legacy['term'], $db['terminals'], $airlineCodes);
        $flights = $this->compareFlights($legacy['flt'], $db['flights'], $terminals['after'], $airlineCodes, $airportCodes);

        return [
            'airlines' => $airlines,
            'airports' => $airports,
            'terminals' => $terminals,
            'flights' => $flights,
            'messages' => $this->msg,
            'has_changes' => (bool) ($airlines['insert'] || $airlines['update'] || $airports['insert'] || $airports['update']
                || $terminals['insert'] || $terminals['default'] || $flights['insert'] || $flights['update']),
        ];
    }

    // ---------------------------------------------------------------- 航空会社

    private function compareAirlines(array $rows, array $db): array
    {
        $insert = $update = $dbOnly = [];
        $seen = [];
        foreach ($rows as $r) {
            $code = strtoupper($r['flt_corp_id']);
            if ($code === '' || isset($seen[$code])) {
                continue;
            }
            $seen[$code] = true;
            $deleted = $r['del_flg'] === '1';
            $d = $db[$code] ?? null;

            if (!$d) {
                if ($deleted) {
                    $this->message('航空会社', $code, "{$r['name']}：現行では削除済みで、DBにもないため追加しません。");
                } else {
                    $insert[] = ['code' => $code, 'name' => $r['name'], 'kana' => $r['name_k'], 'japan_flg' => (int) $r['japan_flg'], 'memo' => $r['memo'] === '' ? null : $r['memo']];
                }
                continue;
            }
            if ($deleted && !$d['deleted']) {
                $this->message('航空会社', $code, "{$d['name']}：現行では削除済みですが、DBの登録を優先して残します。");
            }
            $changes = $this->diff([
                'name' => [$d['name'], $r['name']],
                'kana' => [$d['kana'], $r['name_k']],
                'japan_flg' => [(string) $d['japan_flg'], (string) (int) $r['japan_flg']],
                'memo' => [$d['memo'], $r['memo']],
            ]);
            if ($changes) {
                $update[] = ['code' => $code, 'changes' => $changes];
            }
        }
        foreach ($db as $code => $d) {
            if (!isset($seen[$code]) && !$d['deleted']) {
                $dbOnly[] = ['code' => $code, 'name' => $d['name']];
            }
        }
        return compact('insert', 'update', 'dbOnly');
    }

    // ---------------------------------------------------------------- 出発空港

    private function compareAirports(array $rows, array $db): array
    {
        $insert = $update = $dbOnly = [];
        $seen = [];
        foreach ($rows as $r) {
            $code = strtoupper($r['flt_dpt_id']);
            if ($code === '' || isset($seen[$code])) {
                continue;
            }
            $seen[$code] = true;
            $deleted = $r['del_flg'] === '1';
            $d = $db[$code] ?? null;

            if (!$d) {
                if ($deleted) {
                    $this->message('空港', $code, "{$r['name']}：現行では削除済みで、DBにもないため追加しません。");
                } else {
                    $insert[] = ['code' => $code, 'name' => $r['name'], 'name_kana' => $r['name_k']];
                }
                continue;
            }
            if ($deleted && !$d['deleted']) {
                $this->message('空港', $code, "{$d['name']}：現行では削除済みですが、DBの登録を優先して残します。");
            }
            $changes = $this->diff([
                'name' => [$d['name'], $r['name']],
                'name_kana' => [$d['name_kana'], $r['name_k']],
            ]);
            if ($changes) {
                $update[] = ['code' => $code, 'changes' => $changes];
            }
        }
        foreach ($db as $code => $d) {
            if (!isset($seen[$code]) && !$d['deleted']) {
                $dbOnly[] = ['code' => $code, 'name' => $d['name']];
            }
        }
        return compact('insert', 'update', 'dbOnly');
    }

    // ---------------------------------------------------------------- ターミナル（成田）

    private function compareTerminals(array $rows, array $dbRows, array $airlineCodes): array
    {
        $insert = $default = $dbOnly = [];
        $hndCount = 0;

        // DB：航空会社 => [ターミナル番号 => 行]
        $db = [];
        foreach ($dbRows as $t) {
            $db[$t['airline']][$t['terminal_no']] ??= $t;
        }
        $after = array_map(fn ($byNo) => array_map(fn ($t) => (int) $t['default_flg'], $byNo), $db); // 実行後の状態（航空会社 => [番号 => default_flg]）

        $seen = [];
        foreach ($rows as $r) {
            if ($r['air_port_id'] === self::LEGACY_HND) {
                $hndCount++;
                continue;
            }
            if ($r['air_port_id'] !== self::LEGACY_NRT) {
                continue;
            }
            $code = strtoupper($r['flt_corp_id']);
            $no = self::NRT_TERM_MAP[$r['term_id']] ?? null;
            if ($no === null) {
                $this->message('ターミナル', $code, "現行のターミナル番号 {$r['term_id']} は成田の第1〜第3に読み替えられないため、比較していません。");
                continue;
            }
            $seen[$code] = true;
            if ($r['del_flg'] === '1') {
                if (isset($db[$code][$no])) {
                    $this->message('ターミナル', "{$code} 第{$no}", '現行では削除済みですが、DBの登録を優先して残します。');
                } else {
                    $this->message('ターミナル', "{$code} 第{$no}", '現行では削除済みで、DBにもないため追加しません。');
                }
                continue;
            }
            if (!isset($airlineCodes[$code])) {
                $this->message('ターミナル', "{$code} 第{$no}", '航空会社がDBにも現行の航空会社マスタにもないため、登録できません。');
                continue;
            }
            $isDefault = $r['default_flg'] === '1';
            if (!isset($db[$code][$no])) {
                // 航空会社にターミナルが1件もなければ既定にする。現行で既定なら既定にする（他の行の既定は外す）
                $makeDefault = $isDefault || empty($after[$code]);
                $insert[] = ['code' => $code, 'terminal_no' => $no, 'default_flg' => $makeDefault ? 1 : 0, 'note' => isset($db[$code]) ? 'DBにある別のターミナル: 第' . implode('・第', array_keys($db[$code])) : ''];
                if ($makeDefault && !empty($after[$code])) {
                    $default[] = ['code' => $code, 'terminal_no' => $no, 'reason' => 'new'];
                    $after[$code] = array_map(fn () => 0, $after[$code]);
                }
                $after[$code][$no] = $makeDefault ? 1 : 0;
            } elseif ($isDefault && !$db[$code][$no]['default_flg'] && count($db[$code]) > 1) {
                $default[] = ['code' => $code, 'terminal_no' => $no, 'reason' => 'switch'];
                $after[$code] = array_map(fn () => 0, $after[$code]);
                $after[$code][$no] = 1;
            }
        }
        foreach ($db as $code => $byNo) {
            foreach ($byNo as $no => $t) {
                if (!isset($seen[$code])) {
                    $dbOnly[] = ['code' => $code, 'terminal_no' => $no];
                }
            }
        }
        return compact('insert', 'default', 'dbOnly', 'hndCount', 'after');
    }

    // ---------------------------------------------------------------- 到着便（成田）

    private function compareFlights(array $rows, array $dbRows, array $terminalsAfter, array $airlineCodes, array $airportCodes): array
    {
        $insert = $update = $dbOnly = [];
        $unchanged = 0;
        $filled = [];      // ターミナル0を補完した件数（航空会社 => 件数）
        $missing = [];     // マスタにないため NULL になるもの
        $hndCount = 0;

        // 現行：便名|到着日 => [行]
        $legacy = [];
        foreach ($rows as $r) {
            if ($r['air_port_id'] === self::LEGACY_HND) {
                $hndCount++;
                continue;
            }
            if ($r['air_port_id'] !== self::LEGACY_NRT) {
                continue;
            }
            $carrier = strtoupper($r['flt_corp_id']);
            $flightNo = preg_replace('/^0+(?=\d)/', '', $r['flt_id']);
            $date = str_replace('/', '-', $r['arv_date']);
            if (!preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $date, $m)) {
                $this->message('到着便', "{$carrier}{$flightNo}", "到着日「{$r['arv_date']}」が読めないため比較していません。");
                continue;
            }
            $date = sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
            if (!preg_match('/^(\d{1,2}):(\d{2})/', $r['arv_time'], $tm)) {
                $this->message('到着便', "{$carrier}{$flightNo} {$date}", "到着時刻「{$r['arv_time']}」が読めないため比較していません。");
                continue;
            }
            $legacy["{$carrier}{$flightNo}|{$date}"][] = [
                'carrier' => $carrier, 'flight_no' => $flightNo, 'name' => $carrier . $flightNo, 'date' => $date,
                'dep' => strtoupper($r['flt_dpt_id']) ?: null,
                'time' => sprintf('%02d:%02d:00', $tm[1], $tm[2]),
                'term_raw' => $r['term_id'],
                'deleted' => $r['del_flg'] === '1',
            ];
        }
        if (!$legacy) {
            return compact('insert', 'update', 'dbOnly', 'unchanged', 'filled', 'missing', 'hndCount') + ['range' => null];
        }

        $dates = array_map(fn ($k) => substr($k, strpos($k, '|') + 1), array_keys($legacy));
        $range = [min($dates), max($dates)];

        // DB：便名|到着日 => [行]（現行の期間内だけ）
        $db = [];
        foreach ($dbRows as $f) {
            if ($f['arrive_date'] < $range[0] || $f['arrive_date'] > $range[1]) {
                continue;
            }
            $db["{$f['name']}|{$f['arrive_date']}"][] = $f;
        }

        foreach ($legacy as $key => $lrows) {
            $drows = $db[$key] ?? [];
            [$pairs, $restL, $restD] = $this->pair($lrows, $drows);

            foreach ($pairs as [$l, $d]) {
                if ($l['deleted']) {
                    if (!$d['deleted']) {
                        $this->message('到着便', "{$l['name']} {$l['date']}", '現行では削除済みですが、DBの登録を優先して残します。');
                    }
                    continue;
                }
                if ($d['deleted']) {
                    $this->message('到着便', "{$l['name']} {$l['date']}", 'DBでは削除済みのため、更新していません。');
                    continue;
                }
                $term = $this->terminalFor($l, $terminalsAfter, $filled, $d['terminal_no'], $missing);
                $changes = [];
                if ($l['dep'] !== $d['dep']) {
                    $changes['dep'] = [$d['dep'], $l['dep']];
                }
                if ($l['time'] !== $d['arrive_time']) {
                    $changes['arrive_time'] = [$d['arrive_time'], $l['time']];
                }
                if ($term !== null && $term !== $d['terminal_no']) {
                    $changes['terminal'] = [$d['terminal_no'], $term];
                }
                if ($d['airline'] === null && isset($airlineCodes[$l['carrier']])) {
                    $changes['airline'] = [null, $l['carrier']];
                }
                if ($changes) {
                    $update[] = ['id' => $d['id'], 'name' => $l['name'], 'date' => $l['date'], 'carrier' => $l['carrier'], 'dep' => $l['dep'], 'changes' => $changes];
                    $this->checkMasters($l, $changes, $airlineCodes, $airportCodes, $missing);
                } else {
                    $unchanged++;
                }
            }
            foreach ($restL as $l) {
                if ($l['deleted']) {
                    $this->message('到着便', "{$l['name']} {$l['date']}", '現行では削除済みで、DBにもないため追加しません。');
                    continue;
                }
                $term = $this->terminalFor($l, $terminalsAfter, $filled, null, $missing);
                $insert[] = $l + ['terminal_no' => $term];
                $this->checkMasters($l, ['dep' => 1, 'airline' => 1], $airlineCodes, $airportCodes, $missing);
            }
            foreach ($restD as $d) {
                if (!$d['deleted']) {
                    $dbOnly[] = ['id' => $d['id'], 'name' => $d['name'], 'date' => $d['arrive_date'], 'dep' => $d['dep'], 'time' => $d['arrive_time']];
                }
            }
        }
        foreach ($db as $key => $drows) {
            if (!isset($legacy[$key])) {
                foreach ($drows as $d) {
                    if (!$d['deleted']) {
                        $dbOnly[] = ['id' => $d['id'], 'name' => $d['name'], 'date' => $d['arrive_date'], 'dep' => $d['dep'], 'time' => $d['arrive_time']];
                    }
                }
            }
        }

        usort($insert, fn ($a, $b) => [$a['date'], $a['time'], $a['name']] <=> [$b['date'], $b['time'], $b['name']]);
        usort($update, fn ($a, $b) => [$a['date'], $a['name']] <=> [$b['date'], $b['name']]);
        usort($dbOnly, fn ($a, $b) => [$a['date'], $a['name']] <=> [$b['date'], $b['name']]);

        return compact('insert', 'update', 'dbOnly', 'unchanged', 'filled', 'missing', 'hndCount', 'range');
    }

    /**
     * 同じ便名・到着日の現行の行とDBの行を組にする。
     * 1対1ならそのまま組にする（出発地が違えば出発地の更新になる）。
     * 複数あるときは出発地が同じものどうしを組にし、残りどうしも順に組にする。それでも残ったものは「追加」「DBにだけある」にする。
     */
    private function pair(array $l, array $d): array
    {
        if (count($l) === 1 && count($d) === 1) {
            return [[[$l[0], $d[0]]], [], []];
        }
        $pairs = [];
        foreach ($l as $i => $lr) {
            foreach ($d as $j => $dr) {
                if ($lr['dep'] === $dr['dep']) {
                    $pairs[] = [$lr, $dr];
                    unset($l[$i], $d[$j]);
                    break;
                }
            }
        }
        // 出発地が一致しないものが両方に残ったら、順に組にする（出発地の更新になる。例: 両方 LAS 発になった UA7 の1行を IAH 発に戻す）
        $l = array_values($l);
        $d = array_values($d);
        while ($l && $d) {
            $pairs[] = [array_shift($l), array_shift($d)];
        }
        return [$pairs, $l, $d];
    }

    /**
     * 現行のターミナル番号を今の番号にする。
     * - 1・2・6：第1〜第3。ただし airport_terminals にその組み合わせがなければ、現状のまま（一覧に出す）
     * - 0（不明）：DBにターミナルが入っていればそのまま。空なら航空会社の既定ターミナルで補う
     * 戻り値 null は「変更しない（新規なら NULL）」
     */
    private function terminalFor(array $l, array $after, array &$filled, ?int $current, array &$missing): ?int
    {
        $byNo = $after[$l['carrier']] ?? [];
        if (isset(self::NRT_TERM_MAP[$l['term_raw']])) {
            $no = self::NRT_TERM_MAP[$l['term_raw']];
            if (!isset($byNo[$no])) {
                $k = "ターミナル {$l['carrier']} 第{$no}";
                $missing[$k] = ($missing[$k] ?? 0) + 1;
                return $current;
            }
            return $no;
        }
        if ($current !== null || !$byNo) {
            return $current;
        }
        arsort($byNo); // 既定（1）を先頭に
        $filled[$l['carrier']] = ($filled[$l['carrier']] ?? 0) + 1;
        return (int) array_key_first($byNo);
    }

    private function checkMasters(array $l, array $changes, array $airlineCodes, array $airportCodes, array &$missing): void
    {
        if (isset($changes['airline']) && !isset($airlineCodes[$l['carrier']])) {
            $missing['航空会社 ' . $l['carrier']] = ($missing['航空会社 ' . $l['carrier']] ?? 0) + 1;
        }
        if (isset($changes['dep']) && $l['dep'] !== null && !isset($airportCodes[$l['dep']])) {
            $missing['出発空港 ' . $l['dep']] = ($missing['出発空港 ' . $l['dep']] ?? 0) + 1;
        }
    }

    private function diff(array $pairs): array
    {
        $out = [];
        foreach ($pairs as $col => [$dbVal, $legacyVal]) {
            $a = $dbVal === null || $dbVal === '' ? null : (string) $dbVal;
            $b = $legacyVal === null || $legacyVal === '' ? null : (string) $legacyVal;
            if ($a !== $b) {
                $out[$col] = [$a, $b];
            }
        }
        return $out;
    }

    private function message(string $type, string $code, string $text): void
    {
        $this->msg[] = ['type' => $type, 'code' => $code, 'text' => $text];
    }
}
