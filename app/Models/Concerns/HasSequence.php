<?php

namespace App\Models\Concerns;

trait HasSequence
{
    /**
     * Returns a list of attributes that will cause the sequence to increase when changed.
     *
     * Use `false` to turn-off sequence.
     * Use an empty array to increase on any change.
     */
    protected abstract function sequenced(): array|false;

    /**
     * Boot the has event trait for a model.
     */
    public static function bootHasSequence(): void
    {
        static::saving(function ($model) {
            if ($model->usesSequence()) {
                $model->updateSequence();
            }
        });
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return $this
     */
    public function updateSequence(): static
    {
        $sequenceColumn = $this->getSequenceColumn();

        if (!is_null($sequenceColumn) && !$this->isDirty($sequenceColumn)) {
            if (!$this->exists) {
                $this->{$sequenceColumn} = $this->freshSequence();
            } else if ($this->isDirty($this->sequenced())) {
                $this->{$sequenceColumn}++;
            }
        }

        return $this;
    }

    /**
     * Get a fresh sequence for the model.
     */
    public function freshSequence(): int
    {
        return 0;
    }

    /**
     * Determine if the model uses sequence.
     */
    public function usesSequence(): bool
    {
        return $this->sequenced() !== false;
    }

    /**
     * Get the name of the "sequence" column.
     */
    public function getSequenceColumn(): ?string
    {
        return 'sequence';
    }
}
