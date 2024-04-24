<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreIncidentRequest;
use App\Mail\IncidentReport;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class IncidentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Incident::class);

        return view('incident.create', [
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncidentRequest $request)
    {
        Gate::authorize('create', Incident::class);

        $incident = new Incident($request->validated());

        Mail::to($this->incidentReportRecipients())
            ->cc($incident->reporterMailAddress())
            ->send(new IncidentReport($incident));

        return redirect()->route('dashboard')
            ->with('alert.message', __('Thank-you. Your incident report has been submitted.'));
    }

    /**
     * Get users who should receive incident reports.
     */
    protected function incidentReportRecipients(): Collection
    {
        return User::where('role', Role::TeamLeader)->select(['name', 'email'])->get();
    }
}
