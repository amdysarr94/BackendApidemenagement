<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevisSendNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Devis $devis)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $jour_j = $this->devis->date_demenagement;
        return (new MailMessage)
                    ->line("Vous avez reçu un devis pour votre déménagement du $jour_j.")
                    ->line('Merci d\'utiliser notre site!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'devis_id' => $this->devis->id,
            'nom_client' =>$this->devis->nom_client,
            'date_demenagement' => $this->devis->date_demenagement,
            'prix_total' => $this->devis->prix_total,
            'adresse_actuelle' => $this->devis->adresse_actuelle,
            'nouvelle_adresse' => $this->devis->nouvelle_adresse,
            'description' => $this->devis->description,
        ];
    }
}
