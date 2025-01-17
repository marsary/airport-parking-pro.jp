<?php

namespace App\Rules;

use App\Enums\Coupon\CombinationFlg;
use App\Enums\Coupon\DiscountType;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CombinationFlagRule implements ValidationRule, DataAwareRule
{
    /**
     * バリデーション下の全データ
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    // ...

    /**
     * バリデーション下のデータをセット
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!isset($value) || !isset($this->data['discount_type'])) {
            return;
        }
        // 割引種別が金額のものは他と併用可になります。※500円引きクーポンと1000円引きクーポンの併用など。
        // パーセントのものは他と併用不可です。
        if ($this->data['discount_type'] == DiscountType::PERCENT->value && $value == CombinationFlg::ENABLED->value) {
            $fail('割引種別がパーセントのものは他と併用不可です。');
        }
    }
}
