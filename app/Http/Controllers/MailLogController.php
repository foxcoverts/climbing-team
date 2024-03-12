<?php

namespace App\Http\Controllers;

use App\Models\MailLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class MailLogController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(MailLog::class, 'mail');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('mail-log.index', [
            'mails' => MailLog::orderByDesc('created_at')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MailLog $mail): View
    {
        $mail->markRead()->save();

        return view('mail-log.show', [
            'mail' => $mail,
        ]);
    }

    public function raw(MailLog $mail): Response
    {
        $this->authorize('view', $mail);

        return response($mail->body, headers: [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailLog $mail): RedirectResponse
    {
        $mail->delete();

        return redirect()->route('mail.index')
            ->with('alert.info', __('Mail log deleted.'));
    }
}
