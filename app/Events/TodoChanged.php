<?php

namespace App\Events;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class TodoChanged
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Todo $todo,
        public User $author,
        public array $changes,
    ) {}
}
