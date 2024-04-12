<?php

namespace App\Http\Controllers;

use App\Models\KitCheck;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class KitCheckUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        Gate::authorize('viewAny', KitCheck::class);

        return view('kit-check.user.index', [
            'user' => $user,
            'kitChecks' => $user->kitChecks->load('checked_by'),
        ]);
    }
}
