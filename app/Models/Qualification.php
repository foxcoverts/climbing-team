<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Qualification extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expires_on',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_on' => 'date',
    ];

    public function detail(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNotExpired(Builder $qualifications): void
    {
        $qualifications->where(function (Builder $query): void {
            $query->whereNull('expires_on')
                ->orWhere(function (Builder $or): void {
                    $or->whereDate('expires_on', '>=', Carbon::now());
                });
        });
    }

    public function scopeOrdered(Builder $qualifications): void
    {
        $qualifications->orderBy('expires_on');
    }
}
