<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use App\Models\User;
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

        $qualifications = $user->allQualifications()
            ->with('detail')->ordered();

        return view('qualification.index', [
            'user' => $user,
            'qualifications' => $qualifications->get(),
        ]);
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
}
