<?php

namespace App\iCal\Domain\Enum;

enum Transparency
{
    case Opaque;
    case Transparent;

    public static function default(): static
    {
        return self::Opaque;
    }
}
