<?php

namespace App\Http\Controllers;

use App\Models\KitCheck;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class KitCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', KitCheck::class);

        return view('kit-check.index', [
            'users' => User::orderBy('name')
                ->has('latestKitCheck')
                ->with('latestKitCheck', 'latestKitCheck.checked_by')
                ->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KitCheck $kitCheck): View
    {
        Gate::authorize('view', $kitCheck);

        return view('kit-check.show', [
            'kitCheck' => $kitCheck,
        ]);
    }
}
