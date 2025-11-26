<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationToken extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant, Event $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Token Absensi - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-registration-token',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Lampirkan e-ticket jika sudah digenerate dan path tersimpan di participant
        if ($this->participant->ticket_path) {
            $fullPath = storage_path('app/public/' . $this->participant->ticket_path);

            if (file_exists($fullPath)) {
                return [
                    Attachment::fromPath($fullPath)
                        ->as('e-ticket-' . $this->participant->id . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        }

        return [];
    }
}
