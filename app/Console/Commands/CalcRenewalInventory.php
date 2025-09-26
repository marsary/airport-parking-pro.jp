<?php

namespace App\Console\Commands;

use App\Enums\DealStatus;
use App\Enums\TransactionType;
use App\Models\Deal;
use App\Models\InitialStockQuantity;
use App\Models\LoadUnloadInventoryPerformance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalcRenewalInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calc-renewal-inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リニューアル時在庫状況算出バッチ処理を実行する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('リニューアル時在庫状況算出バッチを実行します。');

        // 初回在庫数からデータを取得する。
        // 開始日付と開始在庫数を取得
        $initialStockQuantity = InitialStockQuantity::latest()->first();


        // 初回は開始日付を対象日付とする
        /** @var Carbon */
        $targetDate = $initialStockQuantity->start_date;
        // 初回は開始在庫数を前日在庫数とする
        $prevStockQuantity = $initialStockQuantity->starting_quantity;

        $today = Carbon::today();

        try {
            while($targetDate <= $today) {
                // 対象日付の入庫数を取得する。
                $loadCount = Deal::whereDate('load_date', $targetDate)
                    ->whereNotIn('status', [
                        DealStatus::NOT_LOADED->value,
                        DealStatus::CANCEL->value,
                    ])
                    ->where('transaction_type', TransactionType::PARKING->value)
                    ->count();

                // 対象日付の出庫数を取得する。
                $unloadCount = Deal::whereDate('unload_date', $targetDate)
                    ->where('status', DealStatus::UNLOADED->value)
                    ->where('transaction_type', TransactionType::PARKING->value)
                    ->count();

                // 入出庫在庫実績にデータを保存する
                $stockQuantity = $prevStockQuantity + $loadCount - $unloadCount;
                LoadUnloadInventoryPerformance::updateOrCreate(
                    ['target_date' => $targetDate],
                    [
                        'load_quantity' => $loadCount,
                        'unload_quantity' => $unloadCount,
                        'stock_quantity' => $stockQuantity,
                    ]
                );

                // 在庫数を前日在庫数とする
                $prevStockQuantity = $stockQuantity;

                // 対象日付を１日進める
                $targetDate->addDay();
            }
            $this->info('バッチ処理を完了しました。');
        } catch (\Throwable $th) {
            Log::error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
            $this->error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
        }
    }
}
