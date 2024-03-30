<?php

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('user.index', [
            'users' => User::orderBy('name')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('user.create', [
            'user' => new User,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);

        $user = User::create(
            array_merge(
                $request->safe()->except(['accreditations']),
                ['password' => '']
            )
        );

        if ($request->safe()->has('accreditations')) {
            $user->accreditations = $request->safe()->accreditations;
        } else {
            $user->accreditations = [];
        }

        event(new Registered($user));

        return redirect()->route('user.booking.invite', $user)
            ->with('alert.info', __('User created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('user.show', [
            'user' => $user
        ]);
    }

    public function sendInvite(User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        if ($user->isActive()) {
            return redirect()->route('user.show', $user)
                ->with('alert.error', __(':Name has already activated their account.', ['name' => $user->name]));
        }

        $user->sendAccountSetupNotification();

        return redirect()->route('user.show', $user)
            ->with('alert.info', __('A new invitation has been sent to :name.', ['name' => $user->name]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->fill($request->safe()->except(['accreditations']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        if ($request->safe()->has('accreditations')) {
            $user->accreditations = $request->safe()->accreditations;
        } else {
            $user->accreditations = [];
        }

        return redirect()->route('user.show', $user)
            ->with('alert.info', __('User updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(DestroyUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('user.index')
            ->with('alert.info', __('User account deleted.'));
    }
}
