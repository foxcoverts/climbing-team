<?php

namespace App\Models;

use App\Enums\Accreditation;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccreditation extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'accreditation',
    ];

    protected $casts = [
        'accreditation' => Accreditation::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
