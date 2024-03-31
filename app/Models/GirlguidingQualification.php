<?php

namespace App\Models;

use App\Contracts\QualificationType;
use App\Enums\GirlguidingScheme;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GirlguidingQualification extends Model implements QualificationType
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scheme',
        'level',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'scheme' => GirlguidingScheme::Climbing,
        'level' => 1,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheme' => GirlguidingScheme::class,
    ];

    public function summary(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => sprintf('%s - Level %d',
                __('app.girlguiding.scheme.'.$attributes['scheme']), $attributes['level']),
        );
    }

    protected function qualification(): MorphOne
    {
        return $this->morphOne(Qualification::class, 'detail');
    }
}
