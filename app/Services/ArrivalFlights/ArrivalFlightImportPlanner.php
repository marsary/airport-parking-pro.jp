<?php

namespace App\Services\ArrivalFlights;

/**
 * 読み込んだ CSV とマスタ・既存の到着便を突き合わせ、
 * 「挿入する行」「更新する行」「確認が必要なもの」を決める。
 *
 * 仕様
 * - キー: 航空会社コード＋便名（= name）＋到着日（到着空港ごと）
 * - キーが一致する既存の便: dep_airport_id / arrive_time / codeshare / terminal_id を更新（値が変わったときだけ）
 *   同じキーの既存の便が複数あるとき（出発地違いの同じ便名）は、出発地が同じ行だけを更新する
 * - 一致しない便: 新規作成
 * - CSV にない既存の便: 何もしない（予約データから参照されているため削除しない）
 * - マスタにない航空会社・空港: null を入れ、確認リストに載せる
 * - ターミナル 0（不明）: その航空会社の到着空港の airport_terminals で補う。なければ null
 *
 * DB にはアクセスしない純粋なクラス。
 */
class ArrivalFlightImportPlanner
{
    public const ISSUE_AIRLINE = '航空会社が airlines にない';
    public const ISSUE_AIRPORT = '出発空港が airports にない';
    public const ISSUE_AIRPORT_EMPTY = '出発空港コードが空';
    public const ISSUE_TERMINAL = 'airport_terminals に航空会社とターミナルの組み合わせがない';
    public const ISSUE_TERMINAL_NONE = 'ターミナル0で、航空会社のターミナル登録もない';
    public const ISSUE_TERMINAL_FILLED = 'ターミナル0のため、航空会社の登録ターミナルで補完';
    public const ISSUE_DUPLICATE = 'CSV内でキーが重複（後の行を採用）';
    public const ISSUE_AMBIGUOUS = '同じ便名・到着日の便が複数あり、出発地が一致しないため更新していません';

    /** マスタに登録しないと変換できない（取り込みを止める）種別 */
    public const BLOCKING = [
        self::ISSUE_AIRLINE,
        self::ISSUE_AIRPORT,
        self::ISSUE_AIRPORT_EMPTY,
        self::ISSUE_TERMINAL,
        self::ISSUE_TERMINAL_NONE,
    ];

    /**
     * @param array<string, array> $records       SunParkingCsvReader::read()['records']
     * @param array<string,int>    $airlines      code => id
     * @param array<string,int>    $airports      code => id
     * @param array<string,int>    $terminals     "airlineId|terminalNo" => airport_terminals.id（到着空港分のみ）
     * @param array<int,int>       $airlineDefaultTerminal  airlineId => airport_terminals.id（ターミナル0の補完用）
     * @param array<int,int>       $terminalNoById  airport_terminals.id => ターミナル番号（表示用）
     * @param array<string, array<int, array>> $existing  key => 既存行の配列（id, dep_airport_id, arrive_time, codeshare, terminal_id）
     */
    public function plan(
        array $records,
        array $airlines,
        array $airports,
        array $terminals,
        array $airlineDefaultTerminal,
        array $terminalNoById,
        array $existing,
        array $duplicates = [],
    ): array {
        $inserts = [];
        $updates = [];
        $unchanged = 0;
        $issues = [];

        foreach ($records as $key => $r) {
            $airlineId = $airlines[$r['carrier']] ?? null;
            if ($airlineId === null) {
                $this->addIssue($issues, self::ISSUE_AIRLINE, $r['carrier'], $r, $r['dep_code'], "airlines に {$r['carrier']} を登録し、airport_terminals に到着空港のターミナルを登録", ['carrier' => $r['carrier']]);
            }

            $depId = null;
            if ($r['dep_code'] === null) {
                $this->addIssue($issues, self::ISSUE_AIRPORT_EMPTY, '(空)', $r, $r['carrier'], 'CSVの出発空港コードを確認');
            } else {
                $depId = $airports[$r['dep_code']] ?? null;
                if ($depId === null) {
                    $this->addIssue($issues, self::ISSUE_AIRPORT, $r['dep_code'], $r, $r['carrier'], "airports に {$r['dep_code']} を登録", ['dep_code' => $r['dep_code']]);
                }
            }

            $terminalId = null;
            if ($airlineId !== null) {
                if ($r['terminal_no'] !== null) {
                    $terminalId = $terminals[$airlineId . '|' . $r['terminal_no']] ?? null;
                    if ($terminalId === null) {
                        $this->addIssue($issues, self::ISSUE_TERMINAL, "{$r['carrier']} / 第{$r['terminal_no']}", $r, $r['dep_code'], "airport_terminals に {$r['carrier']} の第{$r['terminal_no']}ターミナルを登録", ['carrier' => $r['carrier'], 'terminal_no' => $r['terminal_no']]);
                    }
                } else {
                    $terminalId = $airlineDefaultTerminal[$airlineId] ?? null;
                    if ($terminalId === null) {
                        $this->addIssue($issues, self::ISSUE_TERMINAL_NONE, $r['carrier'], $r, $r['dep_code'], "airport_terminals に {$r['carrier']} のターミナルを登録", ['carrier' => $r['carrier']]);
                    } else {
                        $no = $terminalNoById[$terminalId] ?? '?';
                        $this->addIssue($issues, self::ISSUE_TERMINAL_FILLED, "{$r['carrier']} → 第{$no}", $r, null, '対応不要（参考）');
                    }
                }
            }

            $values = [
                'dep_airport_id' => $depId,
                'arrive_time' => $r['arrive_time'],
                'codeshare' => $r['codeshare'],
                'terminal_id' => $terminalId,
            ];

            if (isset($existing[$key])) {
                $targets = $existing[$key];
                // 同じ便名・到着日の便が複数ある（例: UA7 の IAH 発と LAS 発）ときは、出発地が同じ行だけを更新する。
                // 出発地が一致する行がなければ、どれを直すべきか分からないので更新しない
                if (count($targets) > 1) {
                    $targets = array_values(array_filter(
                        $targets,
                        fn ($row) => $depId !== null && (string) ($row['dep_airport_id'] ?? '') === (string) $depId,
                    ));
                    if (!$targets) {
                        $this->addIssue($issues, self::ISSUE_AMBIGUOUS, "{$r['name']} {$r['arrive_date']}", $r, $r['dep_code'], '既存の便の出発地を確認し、必要なら手で直す');
                        continue;
                    }
                }
                $changedIds = [];
                foreach ($targets as $row) {
                    if ($this->differs($row, $values)) {
                        $changedIds[] = (int) $row['id'];
                    } else {
                        $unchanged++;
                    }
                }
                if ($changedIds) {
                    $updates[] = ['ids' => $changedIds, 'values' => $values];
                }
            } else {
                $inserts[] = $values + [
                    'flight_no' => $r['flight_no'],
                    'name' => $r['name'],
                    'airline_id' => $airlineId,
                    'arrive_date' => $r['arrive_date'],
                ];
            }
        }

        foreach ($duplicates as $d) {
            $r = $records[$d['key']];
            $this->addIssue($issues, self::ISSUE_DUPLICATE, "{$r['name']} {$r['arrive_date']}", $r, null, 'CSVの重複行を確認（後の行を採用）');
        }

        $issues = $this->sortIssues($issues);

        return [
            'inserts' => $inserts,
            'updates' => $updates,
            'unchanged' => $unchanged,
            'issues' => $issues,
            'blocking' => count(array_filter($issues, fn ($i) => $i['blocking'])) > 0,
        ];
    }

    private function differs(array $row, array $values): bool
    {
        $norm = fn ($v) => $v === null || $v === '' ? null : (string) $v;
        foreach ($values as $col => $v) {
            if ($norm($row[$col] ?? null) !== $norm($v)) {
                return true;
            }
        }
        return false;
    }

    private function addIssue(array &$issues, string $type, string $code, array $r, ?string $related, string $hint, array $meta = []): void
    {
        $k = $type . '|' . $code;
        if (!isset($issues[$k])) {
            $issues[$k] = [
                'type' => $type,
                'code' => $code,
                'meta' => $meta,                // マスタ登録用SQLを作るための情報（航空会社コード・空港コード・ターミナル番号）
                'terminal_counts' => [],        // CSV上のターミナル番号ごとの便数（0・不明は数えない）
                'blocking' => in_array($type, self::BLOCKING, true),
                'hint' => $hint,
                'count' => 0,
                'first_date' => $r['arrive_date'],
                'last_date' => $r['arrive_date'],
                'related' => [],
                'examples' => [],
            ];
        }
        $i = &$issues[$k];
        $i['count']++;
        if ($r['terminal_no'] !== null) {
            $i['terminal_counts'][$r['terminal_no']] = ($i['terminal_counts'][$r['terminal_no']] ?? 0) + 1;
        }
        $i['first_date'] = min($i['first_date'], $r['arrive_date']);
        $i['last_date'] = max($i['last_date'], $r['arrive_date']);
        if ($related !== null && !in_array($related, $i['related'], true)) {
            $i['related'][] = $related;
        }
        $example = $r['name'] . ($r['dep_code'] ? "（{$r['dep_code']}発）" : '');
        if (count($i['examples']) < 3 && !in_array($example, $i['examples'], true)) {
            $i['examples'][] = $example;
        }
    }

    private function sortIssues(array $issues): array
    {
        $order = array_flip([
            self::ISSUE_AIRLINE, self::ISSUE_AIRPORT, self::ISSUE_AIRPORT_EMPTY,
            self::ISSUE_TERMINAL, self::ISSUE_TERMINAL_NONE, self::ISSUE_AMBIGUOUS, self::ISSUE_TERMINAL_FILLED, self::ISSUE_DUPLICATE,
        ]);
        $issues = array_values($issues);
        usort($issues, fn ($a, $b) => [$order[$a['type']], -$a['count'], $a['code']] <=> [$order[$b['type']], -$b['count'], $b['code']]);
        return $issues;
    }
}
