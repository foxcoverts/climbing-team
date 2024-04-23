<?php

namespace App\Http\Controllers;

use App\Forms\IncidentForm;
use App\Http\Requests\StoreIncidentRequest;
use App\Models\Incident;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IncidentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Incident::class);

        return view('incident.create', [
            'form' => new IncidentForm,
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncidentRequest $request)
    {
        Gate::authorize('create', Incident::class);
    }
}
