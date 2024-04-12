<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreKitCheckRequest;
use App\Http\Requests\UpdateKitCheckRequest;
use App\Models\KitCheck;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', KitCheck::class);

        $kitCheck = new KitCheck([
            'checked_on' => Carbon::now()->toDateString(),
        ]);
        $kitCheck->checked_by = $request->user();

        return view('kit-check.create', [
            'kitCheck' => $kitCheck,
            'checkers' => $this->getCheckers(),
            'users' => User::orderBy('name')->get(),
            'user_ids' => (array) $request->get('users'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKitCheckRequest $request): RedirectResponse
    {
        Gate::authorize('create', KitCheck::class);

        $factory = new KitCheck();

        $created_at = $factory->freshTimestamp();
        $attributes = $request->safe()->except('user_ids');
        $kitChecks = collect($request->safe()->user_ids)
            ->map(fn ($user_id) => [
                'id' => $factory->newUniqueId(),
                'user_id' => $user_id,
                'created_at' => $created_at,
                'updated_at' => $created_at,
                ...$attributes,
            ]);
        KitCheck::insert($kitChecks->all());

        return redirect()->route('kit-check.index')
            ->with('alert.message', __('Kit checks recorded.'));
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KitCheck $kitCheck): View
    {
        Gate::authorize('update', $kitCheck);

        return view('kit-check.edit', [
            'checkers' => $this->getCheckers(),
            'kitCheck' => $kitCheck,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKitCheckRequest $request, KitCheck $kitCheck)
    {
        Gate::authorize('update', $kitCheck);

        $kitCheck->update($request->validated());

        return redirect()->route('kit-check.show', $kitCheck)
            ->with('alert.message', __('Kit check updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KitCheck $kitCheck)
    {
        Gate::authorize('delete', $kitCheck);

        $kitCheck->delete();

        return redirect()->route('kit-check.index')
            ->with('alert.message', __('Kit check deleted.'));
    }

    protected function getCheckers(): Collection
    {
        return User::where('role', Role::TeamLeader)->orderBy('name')->get();
    }
}
