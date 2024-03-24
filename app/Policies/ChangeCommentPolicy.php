<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\ChangeComment;
use App\Models\User;

class ChangeCommentPolicy
{
    function view(User $user, ChangeComment $comment)
    {
        return !$user->isGuest();
    }

    function update(User $user, ChangeComment $comment)
    {
        return $comment->author->is($user);
    }

    function delete(User $user, ChangeComment $comment)
    {
        return $comment->author->is($user) || $user->can('manage', Booking::class);
    }
}
