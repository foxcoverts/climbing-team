<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('user.index', [
            'users' => User::orderBy('name')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
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

        return redirect()->route('user.show', $user)
            ->with('alert.info', __('User created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('user.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
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
        $user->delete();

        return redirect()->route('user.index')
            ->with('alert.info', __('User account deleted.'));
    }
}
