<?php

namespace App\Notifications;

use App\Models\Prestation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrestationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Prestation $prestation)
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
        $prestation = $this->prestation;
        return (new MailMessage)
                    ->line("Votre déménagement sera pris en charge par $prestation->nom_entreprise.")
                    ->line("il aura lieu le $prestation->date_demenagement.")
                    ->line("Merci d'utiliser notre application!");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'Nom du client'=>$this->prestation->nom_client,
            'Entreprise de déménagement'=>$this->prestation->nom_entreprise,
            'date du déménagement'=>$this->prestation->date_demenagement,
        ];
    }
}
