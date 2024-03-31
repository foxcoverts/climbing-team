<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualificationRequest;
use App\Http\Requests\UpdateQualificationRequest;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user): View
    {
        Gate::authorize('viewAny', [Qualification::class, $user]);

        return view('qualification.index', [
            'user' => $user,
            'qualifications' => $user->qualifications()->with('detail')->ordered()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        Gate::authorize('create', [Qualification::class, $user]);

        return view('qualification.create', [
            'user' => $user,
            'qualification' => new Qualification(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQualificationRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('create', [Qualification::class, $user]);

        $permit = ScoutPermit::create($request->safe()->except(['detail_type', 'expires_on']));

        $qualification = new Qualification;
        $qualification->fill($request->safe()->only('expires_on'));
        $qualification->user()->associate($user);
        $qualification->detail()->associate($permit);
        $qualification->save();

        return redirect()->route('user.qualification.show', [$user, $qualification])
            ->with('alert.info', __('Qualification added.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Qualification $qualification): View
    {
        Gate::authorize('view', $qualification);

        return view('qualification.show', [
            'user' => $user,
            'qualification' => $qualification,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Qualification $qualification): View
    {
        Gate::authorize('update', $qualification);

        return view('qualification.edit', [
            'user' => $user,
            'qualification' => $qualification->load('detail'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQualificationRequest $request, User $user, Qualification $qualification)
    {
        Gate::authorize('update', $qualification);

        dd($request->safe());

        $qualification->detail->update($request->safe()->detail);

        $qualification->expires_on = $request->safe()->expires_on;
        $qualification->save();

        return redirect()->route('user.qualification.show', [$user, $qualification])
            ->with('alert.info', __('Qualification updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Qualification $qualification)
    {
        Gate::authorize('delete', $qualification);

        $qualification->delete();

        return redirect()->route('user.qualification.index', $user)
            ->with('alert.info', __('Qualification removed.'));
    }
}
