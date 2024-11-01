<?php

namespace App\Models;

use App\Enums\CommentNotificationOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InvalidArgumentException;

/**
 * This model acts similar to a Pivot table, with no primary key it is instead unique by `user_id`, `notifiable_type` and `notifiable_id`.
 */
class NotificationSettings extends Model
{
    protected $primaryKey = null;

    protected $keyType = null;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment_mail',
        'invite_mail',
        'change_mail',
        'confirm_mail',
        'cancel_mail',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'comment_mail' => CommentNotificationOption::class,
            'invite_mail' => 'boolean',
            'change_mail' => 'boolean',
            'confirm_mail' => 'boolean',
            'cancel_mail' => 'boolean',
        ];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function default(string $setting): bool|CommentNotificationOption
    {
        return match ($setting) {
            'comment_mail' => CommentNotificationOption::All,
            'invite_mail' => true,
            'change_mail' => true,
            'confirm_mail' => true,
            'cancel_mail' => true,
            default => throw new InvalidArgumentException("Unknown setting: $setting"),
        };
    }

    public static function check(User $user, Model $notifiable, string $setting): bool|CommentNotificationOption
    {
        $notifiable_setting = static::where([
            'user_id' => $user->id,
            'notifiable_type' => $notifiable::class,
            'notifiable_id' => $notifiable->id,
        ])->value($setting);
        if (! is_null($notifiable_setting)) {
            return $notifiable_setting;
        }

        $global_setting = static::where([
            'user_id' => $user->id,
            'notifiable_type' => null,
            'notifiable_id' => null,
        ])->value($setting);
        if (! is_null($global_setting)) {
            return $global_setting;
        }

        return static::default($setting);
    }

    /**
     * Determine if two models have the same ID and belong to the same table.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return bool
     */
    public function is($model)
    {
        return ! is_null($model) &&
            $this->getTable() === $model->getTable() &&
            $this->getAttribute('user_id') === $model->getAttribute('user_id') &&
            $this->getAttribute('notifiable_type') === $model->getAttribute('notifiable_type') &&
            $this->getAttribute('notifiable_id') === $model->getAttribute('notifiable_id') &&
            $this->getConnectionName() === $model->getConnectionName();
    }

    /**
     * Set the keys for a select query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    protected function setKeysForSelectQuery($query)
    {
        if (isset($this->attributes[$this->getKeyName()])) {
            return parent::setKeysForSelectQuery($query);
        }

        $query->where('user_id', $this->getOriginal(
            'user_id', $this->getAttribute('user_id')
        ));

        $query->where('notifiable_type', $this->getOriginal(
            'notifiable_type', $this->getAttribute('notifiable_type')
        ));

        $query->where('notifiable_id', $this->getOriginal(
            'notifiable_id', $this->getAttribute('notifiable_id')
        ));

        return $query;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    protected function setKeysForSaveQuery($query)
    {
        return $this->setKeysForSelectQuery($query);
    }

    /**
     * Delete the pivot model record from the database.
     *
     * @return int
     */
    public function delete()
    {
        if (isset($this->attributes[$this->getKeyName()])) {
            return (int) parent::delete();
        }

        if ($this->fireModelEvent('deleting') === false) {
            return 0;
        }

        $this->touchOwners();

        return tap($this->getDeleteQuery()->delete(), function () {
            $this->exists = false;

            $this->fireModelEvent('deleted', false);
        });
    }

    /**
     * Get the query builder for a delete operation on the pivot.
     *
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    protected function getDeleteQuery()
    {
        return $this->newQueryWithoutRelationships()->where([
            'user_id' => $this->getOriginal('user_id', $this->getAttribute('user_id')),
            'notifiable_type' => $this->getOriginal('notifiable_type', $this->getAttribute('notifiable_type')),
            'notifiable_id' => $this->getOriginal('notifiable_id', $this->getAttribute('notifiable_id')),
        ]);
    }

    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId()
    {
        if (isset($this->attributes[$this->getKeyName()])) {
            return $this->getKey();
        }

        return sprintf(
            '%s:%s:%s:%s:%s:%s',
            'user_id', $this->getAttribute('user_id'),
            'notifiable_type', $this->getAttribute('notifiable_type'),
            'notifiable_id', $this->getAttribute('notifiable_id'),
        );
    }

    /**
     * Get a new query to restore one or more models by their queueable IDs.
     *
     * @param  int[]|string[]|string  $ids
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function newQueryForRestoration($ids)
    {
        if (is_array($ids)) {
            return $this->newQueryForCollectionRestoration($ids);
        }

        if (! str_contains($ids, ':')) {
            return parent::newQueryForRestoration($ids);
        }

        $segments = explode(':', $ids);

        return $this->newQueryWithoutScopes()
            ->where($segments[0], $segments[1])
            ->where($segments[2], $segments[3])
            ->where($segments[4], $segments[5]);
    }

    /**
     * Get a new query to restore multiple models by their queueable IDs.
     *
     * @param  int[]|string[]  $ids
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    protected function newQueryForCollectionRestoration(array $ids)
    {
        $ids = array_values($ids);

        if (! str_contains($ids[0], ':')) {
            return parent::newQueryForRestoration($ids);
        }

        $query = $this->newQueryWithoutScopes();

        foreach ($ids as $id) {
            $segments = explode(':', $id);

            $query->orWhere(function ($query) use ($segments) {
                return $query->where($segments[0], $segments[1])
                    ->where($segments[2], $segments[3])
                    ->where($segments[4], $segments[5]);
            });
        }

        return $query;
    }
}
