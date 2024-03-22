<?php

namespace App\Policies;

use App\Models\ChangeField;
use App\Models\User;

class ChangeFieldPolicy
{
    public function view(User $user, ChangeField $field)
    {
        return $user->can('view', $field->change->booking);
    }
}
