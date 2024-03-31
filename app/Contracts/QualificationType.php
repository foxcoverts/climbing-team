<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Casts\Attribute;

interface QualificationType
{
    public function summary(): Attribute;
}
