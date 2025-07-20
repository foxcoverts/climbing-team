<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreKeyRequest;
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
     * Show the form for creating a new key.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Key::class);

        $count = Key::count();
        $key = new Key([
            'name' => sprintf('Key %d', $count + 1),
        ]);
        $key->holder()->associate($request->user());

        return view('key.create', [
            'key' => $key,
            'users' => User::whereNot('role', Role::Suspended->value)->ordered()->get(),
        ]);
    }

    /**
     * Store a newly created key in storage.
     */
    public function store(StoreKeyRequest $request): RedirectResponse
    {
        Gate::authorize('create', Key::class);

        $key = Key::create($request->validated());

        return redirect()->route('key.index')
            ->with('alert.message', __(':Name added.', ['name' => $key->name]));
    }

    /**
     * Display the specified key.
     */
    public function show(Key $key): View
    {
        Gate::authorize('view', $key);

        return view('key.show', [
            'key' => $key,
        ]);
    }

    /**
     * Show the form for editing the specified key.
     */
    public function edit(Request $request, Key $key): View
    {
        Gate::authorize('update', $key);

        return view('key.edit', [
            'ajax' => $request->ajax(),
            'key' => $key,
        ]);
    }

    /**
     * Update the specified key in storage.
     */
    public function update(UpdateKeyRequest $request, Key $key): RedirectResponse
    {
        Gate::authorize('update', $key);

        $key->update($request->validated());

        if ($key->wasChanged()) {
            return redirect()->route('key.index')
                ->with('alert', [
                    'message', __('Key updated.'),
                    '$dispatch' => 'key:updated',
                ]);
        } else {
            return redirect()->route('key.index');
        }
    }

    /**
     * Remove the specified key from storage.
     */
    public function destroy(Key $key): RedirectResponse
    {
        Gate::authorize('delete', $key);

        $key->delete();

        return redirect()->route('key.index')->with('alert.message', __('Key deleted.'));
    }
}
