<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitCheck extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'checked_by_id',
        'checked_on',
        'comment',
    ];

    protected $casts = [
        'checked_on' => 'date:Y-m-d',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checked_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns true if the checked_on is more than 1 year ago.
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->checked_on)->addYear()->isPast();
    }
}
