<?php
namespace App\Services\Deal;

use App\Enums\DealStatus;
use App\Models\Deal;
use App\Models\SystemDate;
use App\Services\PriceTable;
use Carbon\Carbon;

/**
 * 追加精算計算クラス
 */
class ExtraPaymentManager
{
    public $deal;
    public $needPayment = false;
    public $pendingDays;
    public $additionalCharge;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    public function calcPayment()
    {
        if($this->deal->status != DealStatus::LOADED->value) {
            $this->needPayment = false;
            return;
        }
        // 追加精算あるかチェック
        $today = Carbon::today();
        // 出庫予定を過ぎているのに取引ステイタスが出庫済みでない場合：
        if($this->deal->unload_date_plan < $today && $this->deal->status != DealStatus::UNLOADED->value) {
            // 追加精算あり
            $this->needPayment = true;
            // 出庫予定日～本日の期間を計算
            $this->pendingDays = (int) ceil($today->diffInDays($this->deal->unload_date_plan, true));
            // 追加料金を計算
            $this->additionalCharge = PriceTable::calcAdditionalCharge(
                $this->deal->load_date,
                $this->deal->unload_date_plan,
                $this->pendingDays,
                $today,
                $this->deal->agency_id
            );
        }
    }

    public function recalcDealPricesWithExtraPayment()
    {
        if($this->deal->status != DealStatus::LOADED->value) {
            return;
        }
        // 追加精算あるかチェック
        $sysDate = SystemDate::latest()->first();
        if(!$sysDate) {
            throw new \Exception('システム日付が設定されていません。');
        }
        $today = $sysDate->system_date->copy();
    }
}
