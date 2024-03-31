<?php

namespace App\Models;

use App\Contracts\QualificationType;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ScoutPermit extends Model implements QualificationType
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'activity',
        'category',
        'permit_type',
        'restrictions',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'activity' => ScoutPermitActivity::ClimbingAndAbseiling,
        'category' => ScoutPermitCategory::ArtificialTopRope,
        'permit_type' => ScoutPermitType::Leadership,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity' => ScoutPermitActivity::class,
        'category' => ScoutPermitCategory::class,
        'permit_type' => ScoutPermitType::class,
    ];

    public function summary(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => sprintf('%s - %s - %s',
                __('app.scout-permit.activity.'.$attributes['activity']),
                __('app.scout-permit.category.'.$attributes['category']),
                __('app.scout-permit.permit-type.'.$attributes['permit_type'])
            ),
        );
    }

    protected function qualification(): MorphOne
    {
        return $this->morphOne(Qualification::class, 'detail');
    }
}
