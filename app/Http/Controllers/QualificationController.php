<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateQualificationRequest;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QualificationController extends Controller
{
    /**
     * List all qualifications.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Qualification::class);

        $qualifications = Qualification::ordered()->with('detail');

        return view('qualification.index', [
            'currentUser' => $request->user(),
            'user' => null,
            'qualifications' => $qualifications->get(),
        ]);
    }

    /**
     * Show a list of users to create a qualification for.
     */
    public function create(): View
    {
        Gate::authorize('create', Qualification::class);

        return view('qualification.pick-user', [
            'users' => User::ordered()->get(),
        ]);
    }

    /**
     * Display the specified qualification.
     */
    public function show(Request $request, Qualification $qualification): View
    {
        Gate::authorize('view', $qualification);

        return view('qualification.show', [
            'currentUser' => $request->user(),
            'qualification' => $qualification,
        ]);
    }

    /**
     * Show the form for editing the specified qualification.
     */
    public function edit(Qualification $qualification): View
    {
        Gate::authorize('update', $qualification);

        return view('qualification.edit', [
            'qualification' => $qualification->load('detail'),
        ]);
    }

    /**
     * Update the specified qualification in storage.
     */
    public function update(UpdateQualificationRequest $request, Qualification $qualification)
    {
        Gate::authorize('update', $qualification);

        $qualification->detail->update($request->safe()->except(['detail_type', 'expires_on']));

        $qualification->update($request->safe()->only('expires_on'));

        return redirect()->route('qualification.show', $qualification)
            ->with('alert.info', __('Qualification updated.'));
    }

    /**
     * Remove the specified qualification from storage.
     */
    public function destroy(Qualification $qualification)
    {
        Gate::authorize('delete', $qualification);

        $qualification->delete();

        return redirect()->route('user.qualification.index', $qualification->user)
            ->with('alert.info', __('Qualification removed.'));
    }
}
