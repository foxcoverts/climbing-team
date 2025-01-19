<?php

namespace App\Models;

use App\Notifications\KeyTransferredFrom;
use App\Notifications\KeyTransferredTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Key extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    protected $fillable = [
        'name',
        'holder_id',
    ];

    protected $attributes = [
        'name' => 'Key',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function holder(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::updated(function (Key $model): void {
            if ($model->wasChanged('holder_id')) {
                if ($model->holder->id != $model->holder_id) {
                    $model->load('holder');
                }

                $from = User::find($model->getOriginal('holder_id'));

                activity()
                    ->on($model)
                    ->createdAt($model->updated_at)
                    ->withProperties([
                        'old' => ['holder_id' => $model->getOriginal('holder_id')],
                        'attributes' => ['holder_id' => $model->holder_id],
                    ])
                    ->event('transferred')
                    ->log('transferred');

                $model->holder->notify(new KeyTransferredTo($model, $from));
                $from->notify(new KeyTransferredFrom($model));
            }
        });
    }

    public function scopeHeldBy(Builder $keys, User $user): void
    {
        $keys->where('holder_id', $user->id);
    }

    /**
     * Bookings that the given User should be able to view.
     */
    public function scopeForUser(Builder $keys, User $user): void
    {
        if ($user->cannot('manage', Key::class)) {
            $keys->where('holder_id', $user->id);
        }
    }
}
