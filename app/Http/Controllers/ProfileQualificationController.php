<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileQualificationController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', [Qualification::class, $request->user()]);

        $qualifications = $request->user()->allQualifications()
            ->with('detail')->ordered();

        return view('profile.qualification.index', [
            'qualifications' => $qualifications->get(),
        ]);
    }

    public function show(Request $request, Qualification $qualification): View
    {
        if ($qualification->user_id !== $request->user()->id) {
            throw new AuthorizationException;
        }

        Gate::authorize('view', $qualification);

        return view('profile.qualification.show', [
            'qualification' => $qualification,
        ]);
    }
}
