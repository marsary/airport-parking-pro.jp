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
        $sysDate = SystemDate::latest()->first();
        if(!$sysDate) {
            $today = Carbon::today();
        } else {
            $today = $sysDate->system_date->copy();
        }

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

        $table = PriceTable::getPriceTable($this->deal->load_date, $this->deal->unload_date_plan, [], $this->deal->agency_id);

        // 出庫予定を過ぎているのに取引ステイタスが出庫済みでない場合：
        if(($this->deal->overdue || $this->deal->unload_date_plan < $today) && $this->deal->status != DealStatus::UNLOADED->value) {
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

            // 取引の料金情報を更新
            // 追加料金を加えた料金を再計算してセットする
            $price = $table->discountedSubTotal + $this->additionalCharge;
            $tax = $table->tax + roundTax($table->taxType->rate() * $this->additionalCharge);
            [
                'total_price' => $total_price,
                'total_tax' => $total_tax,
            ] = $this->handleGoodPrices($price, $tax);
            $this->deal->fill([
                'price' => $price,
                'tax' => $tax,
                'num_days' => $table->numDays + $this->pendingDays,
                'total_price' => $total_price,
                'total_tax' => $total_tax,
                'overdue' => true,
            ]);
            $this->deal->save();
        }
    }


    public function handleGoodPrices($price, $tax)
    {
        $total_tax = $tax;
        $total_price = $price;
        $dealGoods = $this->deal->dealGoods;
        foreach ($dealGoods as $dealGood) {
            $total_tax += $dealGood->total_tax;
            $total_price += $dealGood->total_price;
        }

        return [
            'total_price' => $total_price,
            'total_tax' => $total_tax,
        ];
    }
}
