<?php

namespace App\Policies;

use App\Models\ChangeComment;
use App\Models\User;

class ChangeCommentPolicy
{
    function view(User $user, ChangeComment $comment)
    {
        return !$user->isGuest();
    }
}
