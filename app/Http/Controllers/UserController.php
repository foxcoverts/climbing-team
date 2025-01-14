<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'users' => User::orderBy('name')->with('keys', 'scoutPermits')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('user.show', [
            'user' => $user->load('latestKitCheck'),
        ]);
    }
}
