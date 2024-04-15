<?php

namespace App\Faker;

use Faker\Provider\Base;

class Scouts extends Base
{
    protected static $sections = [
        'Squirrels',
        'Beavers',
        'Cubs',
        'Scouts',
        'Explorers',
        'Network',
    ];

    protected static function ordinal($number): string
    {
        return match ($number % 100) {
            11, 12, 13 => 'th',
            1, 21, 31, 41, 51, 61, 71, 81, 91 => 'st',
            2, 22, 32, 42, 52, 62, 72, 82, 92 => 'nd',
            3, 32, 33, 43, 53, 63, 73, 83, 93 => 'rd',
            default => 'th'
        };
    }

    public function scoutSection(): string
    {
        return static::randomElement(static::$sections);
    }

    public function scoutGroupName(): string
    {
        $number = static::numberBetween(1, 200);
        $ordinal = static::ordinal($number);
        $section = $this->scoutSection();

        return "$number$ordinal Anytown $section";
    }
}
