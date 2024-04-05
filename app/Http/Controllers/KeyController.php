<?php

namespace App\Http\Controllers;

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
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Key::class);

        return view('key.index', [
            'keys' => Key::orderBy('name')->with('holder')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
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
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKeyRequest $request): RedirectResponse
    {
        Gate::authorize('create', Key::class);

        Key::create($request->validated());

        return redirect()->route('key.index')
            ->with('alert.info', __('Key added.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Key $key): View
    {
        Gate::authorize('view', $key);

        return view('key.show', [
            'key' => $key,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Key $key): View
    {
        Gate::authorize('update', $key);

        return view('key.edit', [
            'key' => $key,
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeyRequest $request, Key $key): RedirectResponse
    {
        Gate::authorize('update', $key);

        $key->update($request->validated());

        return redirect()->back()->with('alert.info', __('Key updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Key $key): RedirectResponse
    {
        Gate::authorize('delete', $key);

        $key->delete();

        return redirect()->route('key.index')->with('alert.info', __('Key deleted.'));
    }
}
