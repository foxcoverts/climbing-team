<?php

namespace App\Casts;

use Carbon\CarbonTimeZone;
use DateTimeZone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class AsTimezone implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?CarbonTimeZone
    {
        if (is_null($value)) {
            return $value;
        }
        if ($value instanceof CarbonTimeZone) {
            return $value;
        }

        return CarbonTimeZone::instance($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return $value;
        }
        if (is_string($value)) {
            $value = new CarbonTimeZone($value);
        }
        if ($value instanceof DateTimeZone) {
            return $value->getName();
        }

        throw new InvalidArgumentException('The given value is not a DateTimeZone instance.');
    }

    /**
     * Get the serialized representation of the value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_null($value)) {
            return $value;
        }
        if ($value instanceof DateTimeZone) {
            return $value->getName();
        }

        throw new InvalidArgumentException('The given value is not a DateTimeZone instance.');
    }
}
