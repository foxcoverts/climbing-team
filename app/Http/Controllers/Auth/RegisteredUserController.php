<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'phone:INTERNATIONAL,GB'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'emergency_name' => ['nullable', 'required_with:emergency_phone', 'string', 'max:100'],
            'emergency_phone' => ['nullable', 'required_with:emergency_name', 'phone:INTERNATIONAL,GB'],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'emergency_name' => $request->emergency_name,
            'emergency_phone' => $request->emergency_phone,
            'timezone' => $request->timezone,
            'role' => Role::TeamLeader,
        ]);
        $user->accreditations = [
            Accreditation::ManageBookings,
            Accreditation::ManageUsers,
        ];

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function edit(User $user): View
    {
        if ($user->password != '') {
            abort(Response::HTTP_FORBIDDEN, 'Invitation expired');
        }

        return view('auth.setup-account', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->password != '') {
            abort(Response::HTTP_FORBIDDEN, 'Invitation expired');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'phone:INTERNATIONAL,GB'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'emergency_name' => ['nullable', 'required_with:emergency_phone', 'string', 'max:100'],
            'emergency_phone' => ['nullable', 'required_with:emergency_name', 'phone:INTERNATIONAL,GB'],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
        ]);

        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'emergency_name' => $request->emergency_name,
            'emergency_phone' => $request->emergency_phone,
            'timezone' => $request->timezone,
            'email_verified_at' => $user->freshTimestamp(),
        ]);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
