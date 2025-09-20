<?php

namespace App\Console\Commands;

use App\Services\Deal\DailyUpdateService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-update {--date= : 日付を指定します(YYYY-MM-DD)} {--days= : 進める日数を指定します}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'システム日付の更新と、保留車両の料金再計算を実行する日次バッチ処理。日付や日数を指定可能。';

    /**
     * Execute the console command.
     */
    public function handle(DailyUpdateService $dailyUpdateService)
    {
        $dateOption = $this->option('date');
        $daysOption = $this->option('days');
        $date = null;
        $days = null;

        if ($dateOption) {
            try {
                $date = Carbon::parse($dateOption);
            } catch (\Exception $e) {
                $this->error('無効な日付形式です。YYYY-MM-DD形式で指定してください。');
                return Command::FAILURE;
            }
        } elseif ($daysOption !== null) {
            if (!ctype_digit($daysOption)) {
                $this->error('進める日数は整数で指定してください。');
                return Command::FAILURE;
            }
            $days = (int)$daysOption;
        }

        $this->info('日次更新バッチを実行します。');
        try {
            $result = $dailyUpdateService->execute($date, $days);

            if ($date) {
                $this->info("システム日付を {$date->format('Y-m-d')} に設定しました。");
            } elseif ($days !== null) {
                $this->info("システム日付を {$days} 日進めました。");
            } else {
                if ($result['isNewDate']) {
                    $this->info('システム日付が設定されていません。今日の日付を設定します。');
                } else {
                    $this->info("システム日付を1日進めました。");
                }
            }

            $this->info('新しいシステム日付: ' . $result['newSysDate']->system_date->format('Y-m-d'));

            // 保留車両の料金再計算を実行
            $this->info('保留車両の料金再計算を実行しました。');
            $this->info('保留車両件数: ' . $result['overDueDealsCount'] . '件');
            $this->info('料金再計算完了件数: ' . $result['updatedCount'] . '件');

            $this->info('日次更新バッチが完了しました。');

        } catch (\Throwable $th) {
            Log::error('日次更新バッチ処理でエラーが発生しました。ロールバック処理によりデータは保存されませんでした: ' . $th->getMessage());
            $this->error('日次更新バッチ処理でエラーが発生しました。ロールバック処理によりデータは保存されませんでした: ' . $th->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
