<?php

namespace App\Rules;

use Illuminate\Validation\Rules\Password as IlluminatePassword;

class Password extends IlluminatePassword
{
    /**
     * Get a list of the password rules that are enabled.
     *
     * @return array<string>
     */
    function getRules(string $attribute = 'password'): array
    {
        $rules = [];

        if ($this->min) {
            $rules[] = __('validation.min.string', ['attribute' => $attribute, 'min' => $this->min]);
        }
        if ($this->max) {
            $rules[] = __('validation.max.string', ['attribute' => $attribute, 'max' => $this->max]);
        }
        if ($this->mixedCase) {
            $rules[] = __('validation.password.mixed', ['attribute' => $attribute]);
        }
        if ($this->letters) {
            $rules[] = __('validation.password.letters', ['attribute' => $attribute]);
        }
        if ($this->symbols) {
            $rules[] = __('validation.password.symbols', ['attribute' => $attribute]);
        }
        if ($this->numbers) {
            $rules[] = __('validation.password.numbers', ['attribute' => $attribute]);
        }

        return $rules;
    }
}
