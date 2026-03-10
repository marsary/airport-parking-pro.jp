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
    public $today;
    public $needPayment = false;
    public $pendingDays;
    public $additionalCharge;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
        $sysDate = SystemDate::latest()->first();
        if(!$sysDate) {
            throw new \Exception('システム日付が設定されていません。');
        }
        $this->today = $sysDate->system_date->copy();
    }

    public function calcPayment()
    {
        if($this->deal->status != DealStatus::LOADED->value) {
            $this->needPayment = false;
            return;
        }
        // 追加精算あるかチェック
        $table = PriceTable::getPriceTable($this->deal->load_date, $this->deal->unload_date_plan, [], $this->deal->agency_id);

        // 出庫予定を過ぎているのに取引ステイタスが出庫済みでない場合：
        if($this->deal->unload_date_plan < $this->today && $this->deal->status != DealStatus::UNLOADED->value) {
            // 追加精算あり
            $this->needPayment = true;
            // 出庫予定日～本日の期間を計算
            $this->pendingDays = (int) ceil($this->today->diffInDays($this->deal->unload_date_plan, true));
            // 追加料金を計算
            $this->additionalCharge = PriceTable::calcAdditionalCharge(
                $this->deal->load_date,
                $this->deal->unload_date_plan,
                $this->pendingDays,
                $this->today,
                $this->deal->agency_id
            );
            // 追加料金を加えた料金と現在の支払額の差額を計算する
            $price = $table->discountedSubTotal + $this->additionalCharge;
            // $tax = $table->tax + roundTax($table->taxType->rate() * $this->additionalCharge);
            $paidPrice = $this->deal->payment ? $this->deal->payment->price : 0;

            $this->additionalCharge = max(0, $price - $paidPrice);
        }
    }

    public function recalcDealPricesWithExtraPayment()
    {
        if($this->deal->status != DealStatus::LOADED->value) {
            return;
        }

        // 追加精算あるかチェック
        $table = PriceTable::getPriceTable($this->deal->load_date, $this->deal->unload_date_plan, [], $this->deal->agency_id);

        // 出庫予定を過ぎているのに取引ステイタスが出庫済みでない場合：
        if(($this->deal->overdue || $this->deal->unload_date_plan < $this->today) && $this->deal->status != DealStatus::UNLOADED->value) {
            // 追加精算あり
            $this->needPayment = true;
            // 出庫予定日～本日の期間を計算
            $this->pendingDays = (int) ceil($this->today->diffInDays($this->deal->unload_date_plan, true));
            // 追加料金を計算
            $this->additionalCharge = PriceTable::calcAdditionalCharge(
                $this->deal->load_date,
                $this->deal->unload_date_plan,
                $this->pendingDays,
                $this->today,
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

    public function recalcDealPricesOnRewindDate($pastDate, $shouldSave = true)
    {
        if(!in_array($this->deal->status, [DealStatus::LOADED->value, DealStatus::UNLOADED->value]) || $pastDate >= $this->today) {
            return;
        }
        if(! $pastDate instanceof Carbon) {
            $pastDate = Carbon::parse($pastDate);
        }
        // 追加精算あるかチェック
        $table = PriceTable::getPriceTable($this->deal->load_date, $this->deal->unload_date_plan, [], $this->deal->agency_id);

        if($this->deal->overdue || $this->deal->unload_date_plan < $this->today) {
            // 追加精算処理済み

            // 取引ステイタスが出庫済みでない場合：
            // 出庫済みで、出庫日が $pastDate とは異なる場合：
            if(
                $this->deal->status == DealStatus::LOADED->value ||
                $this->deal->status == DealStatus::UNLOADED->value && $this->deal->unload_date != $pastDate
            ) {
                // 出庫予定日～基準日の期間を計算
                if($pastDate <= $this->deal->unload_date_plan) {
                    $this->pendingDays = 0;
                    $this->additionalCharge = 0;
                } else {
                    $this->pendingDays = (int) ceil($pastDate->diffInDays($this->deal->unload_date_plan, true));
                    // 追加料金を計算
                    $this->additionalCharge = PriceTable::calcAdditionalCharge(
                        $this->deal->load_date,
                        $this->deal->unload_date_plan,
                        $this->pendingDays,
                        $pastDate,
                        $this->deal->agency_id
                    );
                }
                if($this->deal->unload_date != $pastDate) {
                    // 出庫日が基準日と異なる場合は、出庫日を基準日に変更する
                    $this->deal->unload_date = $pastDate;
                    $this->deal->status = DealStatus::UNLOADED->value;
                }
            }

            if($this->additionalCharge > 0) {
                // 追加精算あり
                $this->needPayment = true;
            } else {
                $this->needPayment = false;
            }

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
                'overdue' => $this->pendingDays > 0,
            ]);

            if($shouldSave) {
                $this->deal->save();
            }

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
