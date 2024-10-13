<?php

namespace App\Http\Controllers;

use App\Models\Change;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ChangeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        Gate::authorize('viewAny', Change::class);

        // @todo include 'changeable.attendees'
        $changes = Change::with('changeable', 'author', 'attendees', 'comments', 'fields')
            ->has('changeable')
            ->orderByDesc('created_at')
            ->cursorPaginate(10);

        return view('change.index', [
            'changes' => $changes,
        ]);
    }
}
