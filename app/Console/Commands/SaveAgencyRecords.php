<?php

namespace App\Console\Commands;

use App\Models\Deal;
use App\Models\SystemDate;
use App\Services\Ledger\AgencyRecordService;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SaveAgencyRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-agency-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '代理店別売上リストや代理店実績のもととなるデータを作成するバッチ処理。対象はシステム日付前日の取引データ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('代理店実績データ作成バッチを実行します。');

        try {
            // システム日付からデータを取得する。
            $sysDate = SystemDate::latest()->first();

            if(!$sysDate) {
                throw new ErrorException('システム日付からデータを取得できませんでした。');
            }

            // システム日付ー１（前日）を取得
            // ※対象日付とする
            /** @var Carbon */
            $targetDate = $sysDate->system_date->copy()->subDays(1);

            // 入出庫在庫実績からデータを取得する
            $deals = $this->getTargetData($targetDate);

            $this->info('対象データ件数: ' . $deals->count() . '件');

            // システム日付前日の代理店実績を保存
            $service = new AgencyRecordService($deals);

            $savedCount = $service->saveData();

            $this->info('保存件数: ' . $savedCount . '件');
            // 完了メッセージ
            $this->info('代理店実績データの保存が完了しました。');

        } catch (\Throwable $th) {
            Log::error('バッチ処理でエラーが発生しました。ロールバック処理によりデータは保存されませんでした: ' . $th->getMessage());
            $this->error('バッチ処理でエラーが発生しました。ロールバック処理によりデータは保存されませんでした: ' . $th->getMessage());
        }
    }

    private function getTargetData($targetDate)
    {
        // ここで対象データを取得する
        $query = Deal::query();

        return $query
            ->has('payment')
            ->whereDate('load_date', $targetDate)
            ->where('office_id', config('const.commons.office_id'))
            ->whereNotNull('agency_id')
            // AgencyRecordServiceで利用するリレーションをEager-loadしてN+1問題を解消
            ->with([
                'office', 'agency', 'member',
                'payment.paymentDetails.paymentMethod',
                'payment.paymentDetails.coupon',
                'memberCar.car.carMaker', 'memberCar.carColor',
                'arrivalFlight.airline', 'arrivalFlight.depAirport'
            ])
            ->orderBy('agency_id', 'asc')
            ->orderBy('deals.id', 'asc')
            ->get();
    }
}
