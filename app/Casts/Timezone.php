<?php

namespace App\Casts;

use Carbon\CarbonTimeZone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Timezone implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): CarbonTimeZone
    {
        if ($value instanceof CarbonTimeZone) {
            return $value;
        }
        return new CarbonTimeZone($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_string($value)) {
            $value = new CarbonTimeZone($value);
        }
        if (!$value instanceof CarbonTimeZone) {
            throw new InvalidArgumentException('The given value is not a CarbonTimeZone instance.');
        }
        return $value->getName();
    }

    /**
     * Get the serialized representation of the value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (!$value instanceof CarbonTimeZone) {
            throw new InvalidArgumentException('The given value is not a CarbonTimeZone instance.');
        }
        return $value->getName();
    }
}
