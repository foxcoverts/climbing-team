<?php

namespace App\Rules;

use Carbon\CarbonTimeZone;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class Timezone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            CarbonTimeZone::instance($value);
        } catch (Exception $exception) {
            $fail('validation.timezone')->translate();
        }
    }
}
