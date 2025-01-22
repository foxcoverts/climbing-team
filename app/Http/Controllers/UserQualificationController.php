<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualificationRequest;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserQualificationController extends Controller
{
    /**
     * List users who have qualifications
     */
    public function index(Request $request, User $user): View
    {
        Gate::authorize('viewAny', [Qualification::class, $user]);

        $qualifications = $user->allQualifications()
            ->with('detail')->ordered();

        return view('qualification.index', [
            'currentUser' => $request->user(),
            'user' => $user,
            'qualifications' => $qualifications->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user): View
    {
        Gate::authorize('create', [Qualification::class, $user]);

        return view('qualification.create', [
            'user' => $user,
            'qualification' => new Qualification,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQualificationRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('create', [Qualification::class, $user]);

        $detail_type = $request->safe()->detail_type;

        $detail = new $detail_type($request->safe()->except(['detail_type', 'expires_on']));
        $detail->save();

        $qualification = new Qualification;
        $qualification->fill($request->safe()->only('expires_on'));
        $qualification->user()->associate($user);
        $qualification->detail()->associate($detail);
        $qualification->save();

        return redirect()->route('qualification.show', $qualification)
            ->with('alert.info', __('Qualification added.'));
    }
}
