<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Correspondance;

class CorrespondanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correspondance;

    public function __construct(Correspondance $correspondance)
    {
        $correspondance->load('sender'); // Charge la relation "sender"
        $this->correspondance = $correspondance;
    }

    // SUPPRIMER TOUTES LES AUTRES MÉTHODES (envelope, content, attachments)
    // ET GARDER UNIQUEMENT build()

// Dans CorrespondanceMail.php
// CorrespondanceMail.php
public function build()
{
    return $this->subject($this->correspondance->objet)
               ->view('emails.correspondance');
}
}