<?php

namespace App\Console\Commands;

use App\Enums\DealStatus;
use App\Enums\TransactionType;
use App\Models\Deal;
use App\Models\LoadUnloadInventoryPerformance;
use App\Models\SystemDate;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalcDailyInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calc-daily-inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取引テーブルから入庫数・出庫数を取得し、対象日付の入庫数・出庫数・在庫数を算出する日次バッチ処理';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('日次在庫状況算出バッチを実行します。');

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
            $inventory = $this->getInventory($targetDate);

            // 初回はシステム日付の前々日在庫数を前日在庫数とする
            $prevStockQuantity = $inventory->stock_quantity;

            // システム日付前日までの処理
            $this->calcAndSaveDailyInventoryBeforeSysDate($targetDate, $prevStockQuantity);


            // 以下、対象日付から180日後まで繰り返し行う。
            $endDate = $targetDate->copy()->addDays(180);

            while($targetDate <= $endDate) {
                $this->calcAndSaveDailyInventoryAfterSysDate($targetDate, $prevStockQuantity);
            }


            $this->info('バッチ処理を完了しました。');

        } catch (\Throwable $th) {
            Log::error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
            $this->error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
        }
    }

    private function getInventory(Carbon $targetDate)
    {
        // 入出庫在庫実績からデータを取得する
        // 対象日付の前日（システム日付の前々日）
        // のデータを取得する
        $inventory = LoadUnloadInventoryPerformance::whereDate('target_date', $targetDate->copy()->subDays(1))
            ->first();

        // データがなかった場合はエラー
        if(!$inventory) {
            throw new ErrorException('入出庫在庫実績からデータを取得できませんでした。');
        }
        return $inventory;
    }

    private function calcAndSaveDailyInventoryBeforeSysDate(Carbon $targetDate, &$prevStockQuantity)
    {
        // 対象日付の入庫実績を取得する。
        $loadCount = Deal::whereDate('load_date', $targetDate)
            ->whereNotIn('status', [
                DealStatus::NOT_LOADED->value,
                DealStatus::CANCEL->value,
            ])
            ->where('transaction_type', TransactionType::PARKING->value)
            ->count();

        // 対象日付の出庫実績を取得する。
        $unloadCount = Deal::whereDate('unload_date', $targetDate)
            ->where('status', DealStatus::UNLOADED->value)
            ->where('transaction_type', TransactionType::PARKING->value)
            ->count();

        // 当日在庫数＝前日在庫数＋当日入庫数ー当日出庫数
        $stockQuantity = $prevStockQuantity + $loadCount - $unloadCount;
        // 入出庫在庫実績にデータを保存する
        $this->saveInventory($stockQuantity, $loadCount, $unloadCount, $targetDate);

        // 在庫数を前日在庫数とする
        $prevStockQuantity = $stockQuantity;

        // 対象日付を１日進める
        $targetDate->addDay();
    }

    private function calcAndSaveDailyInventoryAfterSysDate(Carbon $targetDate, &$prevStockQuantity)
    {
        // 対象日付の入庫実績を取得する。
        $loadCount = Deal::whereDate('load_date', $targetDate)
            ->where('transaction_type', TransactionType::PARKING->value)
            ->count();

        // 対象日付（出庫予定日が対象日付）の出庫実績を取得する。
        $unloadCount = Deal::whereDate('unload_date_plan', $targetDate)
            ->where('status', DealStatus::UNLOADED->value)
            ->where('transaction_type', TransactionType::PARKING->value)
            ->count();

        // 当日在庫数＝前日在庫数＋当日入庫数ー当日出庫数
        $stockQuantity = $prevStockQuantity + $loadCount - $unloadCount;
        // 入出庫在庫実績にデータを保存する
        $this->saveInventory($stockQuantity, $loadCount, $unloadCount, $targetDate);

        // 在庫数を前日在庫数とする
        $prevStockQuantity = $stockQuantity;

        // 対象日付を１日進める
        $targetDate->addDay();
    }

    private function saveInventory(int $stockQuantity, int $loadCount, int $unloadCount, Carbon $targetDate)
    {
        $inventory = LoadUnloadInventoryPerformance::whereDate('target_date', $targetDate)
            ->first();

        if($inventory) {
            $inventory->load_quantity = $loadCount;
            $inventory->unload_quantity = $unloadCount;
            $inventory->stock_quantity = $stockQuantity;
            $inventory->save();
        } else {
            LoadUnloadInventoryPerformance::create([
                'target_date' => $targetDate,
                'load_quantity' => $loadCount,
                'unload_quantity' => $unloadCount,
                'stock_quantity' => $stockQuantity,
            ]);
        }
    }
}
