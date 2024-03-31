<?php

namespace App\Models;

use App\Contracts\QualificationType;
use App\Enums\MountainTrainingAward;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MountainTrainingQualification extends Model implements QualificationType
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'award',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'award' => MountainTrainingAward::class,
    ];

    public function summary(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => __('app.mountain-training.award.'.$attributes['award']),
        );
    }

    protected function qualification(): MorphOne
    {
        return $this->morphOne(Qualification::class, 'detail');
    }
}
