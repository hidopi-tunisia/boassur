<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleReservation extends Notification
{
    use Queueable;

    public $resa;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $resa)
    {
        $this->resa = $resa;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirmation de votre souscription')
            ->greeting('Bonjour '.$notifiable->nom_complet.',')
            ->line('Nous avons reçu votre souscription.')
            ->action('Votre contrat', $this->resa->url)
            ->salutation('Cordialement,<br>L\'équipe de Presence Assistance Tourisme.')
            ->markdown(
                'vendor.notifications.email',
                ['site' => $this->resa->commande->site]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
