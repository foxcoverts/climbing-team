<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateKeyRequest;
use App\Models\Key;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KeyController extends Controller
{
    /**
     * Display a listing of the key.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Key::class);

        return view('key.index', [
            'keys' => Key::forUser($request->user())
                ->orderBy('name')->with('holder')->get(),
        ]);
    }

    /**
     * Show the form for transferring the key to another holder.
     */
    public function edit(Request $request, Key $key): View
    {
        Gate::authorize('transfer', $key);

        return view('key.edit', [
            'ajax' => $request->ajax(),
            'key' => $key,
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateKeyRequest $request, Key $key): RedirectResponse
    {
        Gate::authorize('transfer', $key);

        $key->holder()->associate($request->holder_id);
        $key->save();

        if ($key->wasChanged('holder_id')) {
            return redirect()->route('key.index')
                ->with('alert', [
                    'message' => __(':Key transferred to :holder.', [
                        'key' => $key->name,
                        'holder' => $key->holder->name,
                    ]),
                    '$dispatch' => 'key:updated',
                ]);
        } else {
            return redirect()->route('key.index');
        }
    }
}
