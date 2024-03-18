<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user->hasVerifiedEmail()) {
            $request->session()->put('alert.info', __('Your email address has been verified.'));
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user->markEmailAsVerified()) {
            event(new Verified($request->user));
        }

        $request->session()->put('alert.info', __('Your email address has been verified.'));
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
