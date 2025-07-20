<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\ChangeComment;
use App\Models\User;

class ChangeCommentPolicy
{
    public function view(User $user, ChangeComment $comment)
    {
        if ($comment->author->is($user)) {
            return true;
        }

        return ! $user->isGuest() && ! $user->isSuspended();
    }

    public function update(User $user, ChangeComment $comment)
    {
        return $comment->author->is($user) || $user->can('manage', Booking::class);
    }

    public function delete(User $user, ChangeComment $comment)
    {
        return $comment->author->is($user) || $user->can('manage', Booking::class);
    }
}
