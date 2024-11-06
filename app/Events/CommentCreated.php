<?php

namespace App\Events;

use App\Models\ChangeComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class CommentCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Model $parent,
        public ChangeComment $comment,
    ) {}
}
