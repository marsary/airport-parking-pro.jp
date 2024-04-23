<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ZipcodeRule implements ValidationRule
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
        if (!preg_match('/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/', $value)) {
            $fail('validation.zip')->translate();
        }
    }
}
