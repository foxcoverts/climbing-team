<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SetupAccountLinkRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SetupAccountLinkController extends Controller
{

    public function create(User $user): View
    {
        return view('auth.setup-account-expired', [
            'user' => $user,
        ]);
    }

    public function store(SetupAccountLinkRequest $request, User $user): RedirectResponse
    {
        $user->sendAccountSetupNotification();

        return redirect()->route('login')
            ->with('alert.info', __('New account setup link sent'));
    }
}
