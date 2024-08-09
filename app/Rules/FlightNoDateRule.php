<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class FlightNoDateRule implements ValidationRule, DataAwareRule
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
        if(!isset($value) || !isset($this->data['arrive_date'])) {
            return;
        }
        $exists = DB::table('arrival_flights')
            ->where('flight_no', $value)
            ->where('arrive_date', $this->data['arrive_date'])
            ->where('airline_id', $this->data['airline_id'])
            ->exists();

        if (!$exists) {
            $fail('validation.flight_no_date')->translate();
        }
    }
}
