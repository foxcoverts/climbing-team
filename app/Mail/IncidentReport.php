<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class IncidentReport extends Mailable
{
    public function __construct(
        public Incident $incident,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Incident Report'),
            replyTo: [new Address(
                $this->incident->reporterEmail,
                $this->incident->reporterName
            )],
            tags: ['incident'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.incident.report',
            with: [
                'incident' => $this->incident,
            ],
        );
    }
}
