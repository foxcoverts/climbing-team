<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;

class NewsPostPolicy
{
    public function manage(User $user)
    {
        return $user->isTeamLeader();
    }

    public function viewAny(?User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->can('manage', NewsPost::class);
    }

    public function view(?User $user, NewsPost $post)
    {
        return true;
    }

    public function update(User $user, NewsPost $post)
    {
        return $user->can('manage', $post);
    }

    public function delete(User $user, NewsPost $post)
    {
        return $user->can('manage', $post);
    }
}
