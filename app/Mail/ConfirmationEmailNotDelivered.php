<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmailNotDelivered extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande->loadMissing(['site', 'voyageur', 'reservation']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('mail souscription '.$this->commande->site->nom.' non délivré')
            ->view('admin.emails.mail-non-delivre')
            ->with([
                'souscription' => $this->commande->reservation->num_souscription,
                'nom' => $this->commande->voyageur->nom_complet,
                'email' => $this->commande->voyageur->email,
                'telephone' => $this->commande->voyageur->telephone,
                'site' => $this->commande->site->url,
            ]);
    }
}
