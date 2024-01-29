<?php

namespace App\Notifications;

use App\Models\DemandeDevis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeDevisSendNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private DemandeDevis $demandeDevis)
    {
        $this->demandeDevis = $demandeDevis;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $nomClient = $this->demandeDevis->nom_client;
        $adresseNow = $this->demandeDevis->adresse_actuelle;
        $adresseNew = $this->demandeDevis->nouvelle_adresse;
        $bagage = $this->demandeDevis->informations_bagages;
        $jour_j = $this->demandeDevis->date_demenagement;
        return (new MailMessage)
                    ->line("Vous avez reçu une demande de devis de $nomClient.")
                    ->line("Les informations de la demande de devis :")
                    ->line("Adresse actuelle du client : $adresseNow")
                    ->line("Nouvelle adresse du client : $adresseNew")
                    ->line("Informations sur les bagages : $bagage")
                    ->line("Date du déménagement : $jour_j")
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
            'Nom du déménageur'=> $this->demandeDevis->nom_entreprise,
            'Nom du client'=> $this->demandeDevis->nom_client,
            'Adresse actuelle du client' => $this->demandeDevis->adresse_actuelle,
            'Nouvelle adresse du client'=> $this->demandeDevis->nouvelle_adresse,
            'Information sur les bagages'=> $this->demandeDevis->informations_bagages, 
            'Date du déménagement'=>$this->demandeDevis->date_demenagement,

        ];
    }
}
