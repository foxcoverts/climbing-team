<?php

namespace App\Http\Controllers;

use App\Models\MailLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class MailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', MailLog::class);

        return view('mail-log.index', [
            'mails' => MailLog::orderByDesc('created_at')
                ->cursorPaginate(5),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MailLog $mail): View
    {
        Gate::authorize('view', $mail);

        $mail->markRead()->save();

        return view('mail-log.show', [
            'mail' => $mail,
        ]);
    }

    public function raw(MailLog $mail): Response
    {
        Gate::authorize('view', $mail);

        return response($mail->rawBody, headers: [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailLog $mail): RedirectResponse
    {
        Gate::authorize('delete', $mail);

        $mail->delete();

        return redirect()->route('mail.index')
            ->with('alert.info', __('Mail log deleted.'));
    }
}
