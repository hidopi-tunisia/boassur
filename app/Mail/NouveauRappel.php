<?php

namespace App\Mail;

use App\Models\Rappel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouveauRappel extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance de Rappel
     *
     * @var \App\Models\Rappel
     */
    public $rappel;

    /**
     * Create a new message instance.
     *
     * @var \App\Models\Rappel
     *
     * @return void
     */
    public function __construct(Rappel $rappel)
    {
        $this->rappel = $rappel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_CSE_FROM_ADDRESS', 'MAIL_FROM_ADDRESS'), 'Presence Assistance Tourisme - CSE')
            ->subject('Nouvelle demande de rappel CSE')
            ->view('admin.emails.rappel');
    }
}
