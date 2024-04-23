<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!isset($value)) {
            return;
        }
        if (!preg_match('/^(([0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4})|([0-9]{8,11}))$/', $value)) {
            $fail('validation.tel')->translate();
        }
    }
}
