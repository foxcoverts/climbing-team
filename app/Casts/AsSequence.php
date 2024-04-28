<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class AsSequence implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): int
    {
        return (int) $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if (array_key_exists($key, $attributes) && ($value < $attributes[$key])) {
            throw new InvalidArgumentException("Attribute `$key` may not be decreased. Was: $attributes[$key]. Given: $value.");
        }
        if ($value < 0) {
            throw new InvalidArgumentException("Attribute `$key` cannot be negative. Given: $value.");
        }

        return (int) $value;
    }
}
