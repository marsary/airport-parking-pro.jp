<?php

namespace App\Services\ArrivalFlights;

use RuntimeException;

/**
 * Sun_Parking_YYYY-MM-DD.csv を読み込み、取り込み用のレコードに整える。
 *
 * - 文字コードは UTF-8（BOM あり・なし）と Shift_JIS に対応
 * - 便名の先頭の 0 は外す（"055" → "55"）。DB の flight_no / name に合わせるため
 * - キー（航空会社コード＋便名＋到着日）が CSV 内で重複したら、後の行を採用
 * - ターミナルは 1〜3 のみ数値として扱い、0・空・その他は null（不明）
 * - 航空会社コード・便名・到着日・到着時刻が読めない行は取り込まず、エラーとして返す
 *
 * Laravel に依存しない純粋なクラスなので、単体でテストできる。
 */
class SunParkingCsvReader
{
    public const REQUIRED_COLUMNS = [
        'Arrival_Terminal',
        'Arrival_Date',
        'Arrival_Time',
        'IATA_Carrier_Code',
        'Flight_No',
        'Codeshare',
        'Departure_Airport_Code',
    ];

    /**
     * @return array{
     *   records: array<string, array>,   // key => record
     *   errors: array<int, array{line:int, reason:string}>,
     *   duplicates: array<int, array{line:int, key:string}>,
     *   total: int
     * }
     */
    public function read(string $path): array
    {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('CSVファイルを読み込めませんでした。');
        }

        return $this->readString($content);
    }

    public function readString(string $content): array
    {
        $content = $this->toUtf8($content);

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $header = fgetcsv($handle, 0, ',', '"', '');
        if ($header === false) {
            throw new RuntimeException('CSVが空です。');
        }
        $header = array_map('trim', $header);

        $missing = array_diff(self::REQUIRED_COLUMNS, $header);
        if ($missing) {
            throw new RuntimeException('CSVに次の列がありません: ' . implode(', ', $missing));
        }
        $index = array_flip($header);

        $records = [];
        $errors = [];
        $duplicates = [];
        $total = 0;
        $line = 1;

        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $line++;
            if ($row === [null] || trim(implode('', $row)) === '') {
                continue;
            }
            $total++;

            $get = fn (string $col) => trim((string) ($row[$index[$col]] ?? ''));

            $carrier = strtoupper($get('IATA_Carrier_Code'));
            $flightNo = preg_replace('/^0+(?=\d)/', '', $get('Flight_No'));
            $date = str_replace('/', '-', $get('Arrival_Date'));

            if ($carrier === '' || $flightNo === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $errors[] = ['line' => $line, 'reason' => '航空会社コード・便名・到着日のいずれかが読めないため、取り込みませんでした。'];
                continue;
            }

            $time = $this->toTime($get('Arrival_Time'));
            if ($time === false) {
                // 既存の時刻を空で上書きしないよう、この行は取り込まない
                $errors[] = ['line' => $line, 'reason' => "到着時刻「{$get('Arrival_Time')}」が読めないため、取り込みませんでした。"];
                continue;
            }

            $terminal = $get('Arrival_Terminal');
            $codeshare = $get('Codeshare');
            $dep = strtoupper($get('Departure_Airport_Code'));

            $record = [
                'line' => $line,
                'carrier' => $carrier,
                'flight_no' => $flightNo,
                'name' => $carrier . $flightNo,
                'arrive_date' => $date,
                'arrive_time' => $time,
                'terminal_no' => in_array($terminal, ['1', '2', '3'], true) ? (int) $terminal : null,
                'terminal_raw' => $terminal,
                'codeshare' => $codeshare === '' ? null : $codeshare,
                'dep_code' => $dep === '' ? null : $dep,
            ];

            $key = self::key($record['name'], $date);
            if (isset($records[$key])) {
                $duplicates[] = ['line' => $line, 'key' => $key];
            }
            $records[$key] = $record; // 後の行を採用
        }
        fclose($handle);

        return compact('records', 'errors', 'duplicates', 'total');
    }

    public static function key(string $name, string $date): string
    {
        return $name . '|' . $date;
    }

    /** "1650" / "650" / "16:50" / "16:50:00" → "16:50:00"。空なら null、読めなければ false */
    private function toTime(string $value): string|null|false
    {
        if ($value === '') {
            return null;
        }
        if (preg_match('/^(\d{1,2}):?(\d{2})(?::(\d{2}))?$/', $value, $m)) {
            [$h, $i, $s] = [(int) $m[1], (int) $m[2], (int) ($m[3] ?? 0)];
            if ($h < 24 && $i < 60 && $s < 60) {
                return sprintf('%02d:%02d:%02d', $h, $i, $s);
            }
        }
        return false;
    }

    private function toUtf8(string $content): string
    {
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            return substr($content, 3);
        }
        if (mb_check_encoding($content, 'UTF-8')) {
            return $content;
        }
        return mb_convert_encoding($content, 'UTF-8', 'SJIS-win');
    }
}
