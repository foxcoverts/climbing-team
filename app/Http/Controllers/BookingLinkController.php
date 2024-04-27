<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingLinkController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view', $request->user());

        $currentUser = $request->user();
        if (is_null($currentUser->ical_token)) {
            $currentUser->ical_token = User::generateToken();
            $currentUser->save();
        }

        return view('booking.links', [
            'currentUser' => $currentUser,
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Gate::authorize('update', $request->user());

        $currentUser = $request->user();
        $currentUser->ical_token = User::generateToken();
        $currentUser->save();

        return redirect()->route('booking.links')
            ->with('alert.message', __('Your calendar links have been reset.'));
    }
}
