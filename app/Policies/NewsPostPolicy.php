<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;

class NewsPostPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, NewsPost $post)
    {
        return true;
    }
}
