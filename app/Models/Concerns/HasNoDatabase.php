<?php

namespace App\Models\Concerns;

use BadFunctionCallException;

trait HasNoDatabase
{
    public static function bootHasNoDatabase(): void
    {
        static::creating(fn () => false);
        static::updating(fn () => false);
        static::saving(fn () => false);
        static::deleting(fn () => false);
    }

    public function newQuery()
    {
        throw new BadFunctionCallException('Cannot query a model with no database.');
    }

    public function newEloquentBuilder($query)
    {
        throw new BadFunctionCallException('Cannot query a model with no database.');
    }

    public function getConnection()
    {
        throw new BadFunctionCallException('Cannot get connection for a model with no database.');
    }

    public function getConnectionName()
    {
        return null;
    }

    public function getQueueableRelations()
    {
        return [];
    }

    public function getIncrementing()
    {
        return false;
    }
}
