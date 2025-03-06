<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProfileQualificationController extends Controller
{
    public function show(Request $request): View
    {
        Gate::authorize('viewAny', [Qualification::class, $request->user()]);

        $qualifications = $request->user()->allQualifications()
            ->with('detail')->ordered();

        return view('qualification.index', [
            'currentUser' => $request->user(),
            'user' => $request->user(),
            'qualifications' => $qualifications->get(),
        ]);
    }
}
