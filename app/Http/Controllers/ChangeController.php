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

        $changes = Change::with('booking', 'booking.attendees', 'author', 'attendees', 'comments', 'fields')
            ->has('booking')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('change.index', [
            'changes' => $changes,
        ]);
    }
}
