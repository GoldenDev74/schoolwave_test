<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\Correspondance;

class CorrespondanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correspondance;

    public function __construct(Array $correspondance)
    { // Charge la relation "sender"
        $this->correspondance = $correspondance;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@webees.org', 'WEBEES ADMINISTRATION '),
            subject: $this->correspondance["subject"],
        );
    }



    public function content(): Content
    {
        return new Content(
            view: 'email.correspondance',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}