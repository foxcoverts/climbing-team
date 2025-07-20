<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can manage documents.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader();
    }

    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        return ! $user->isSuspended();
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        return ! $user->isSuspended();
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return $user->can('manage', Document::class);
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->can('manage', Document::class);
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->can('manage', Document::class);
    }

    /**
     * Determine whether the user can view trashed models.
     */
    public function viewTrashed(User $user): bool
    {
        return $user->can('manage', Document::class);
    }

    /**
     * Determine whether the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->can('manage', Document::class);
    }

    /**
     * Determine whether the user can permanently delete the document.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->can('manage', Document::class);
    }
}
