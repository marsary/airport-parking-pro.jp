<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * 対象テーブルへのシーディングをCSVファイルから行うクラス
 * 個別の Seederクラスから seedメソッドを実行する
 */
class SeedFromCSV
{
    /**
     * @param string テーブル名
     * @param string $fileName ./csv 以下にあるファイル名
     * @param array<string> $columns インポート先のカラム名の配列
     * @return void
     */
    public static function seed(string $tableName, string $fileName, array $columns = [])
    {
        $output = new ConsoleOutput();

        $output->writeln($tableName . " のレコード作成を開始します...");

        $memberSplFileObject = new \SplFileObject(__DIR__ . '/csv/' . $fileName);
        $memberSplFileObject->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );
        $header = [];
        $count = 0;
        foreach ($memberSplFileObject as $key => $row) {
            if ($key === 0) {
                $header = $row;
                continue;
            }
            $data = [];
            foreach ($columns as $column) {
                $idx = array_search($column, $header);
                $data[$column] = self::convNull($row[$idx]);
            }
            DB::table($tableName)->insert($data);

            $count++;
        }

        $output->writeln($tableName . " レコードを{$count}件、作成しました。");
    }

    private static function convNull($value)
    {
        if(isBlank($value)) {
            return null;
        }
        return $value;
    }
}
