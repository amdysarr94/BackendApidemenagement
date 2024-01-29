<?php

namespace App\Listeners;
use DateTime;
use App\Models\User;
use App\Models\Offre;
use App\Models\Prestation;
use App\Models\Souscription;
use Illuminate\Support\Carbon;
use App\Models\InformationsSupp;
use App\Events\SouscriptionValiderEvent;
use App\Notifications\PrestationNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SouscriptionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SouscriptionValiderEvent $event): void
    {
        //$souscription->nom_offre => [Offres]=>user_id
        $souscriptionId = $event->souscriptionId;
        $souscription = Souscription::findOrFail($souscriptionId);
        $prestation = new Prestation();
        //détermination du nom de l'entreprise
        $offre = Offre::where('nom_offre', $souscription->nom_offre)->get()->first();
        $infosup = InformationsSupp::where('user_id', $offre->user_id)->get()->first();
        $nom_demenageur = $infosup->nom_entreprise;
        $prestation->nom_entreprise = $nom_demenageur;
        $prestation->nom_client = $souscription->nom_client;
        $prestation->prix_total = $souscription->prix_total;
        $prestation->adresse_actuelle = $souscription->adresse_actuelle;
        $prestation->nouvelle_adresse = $souscription->nouvelle_adresse;
        $prestation->description = $souscription->description;

        $prestation->date_demenagement = $souscription->date_demenagement;
        $jour_j  = Carbon::parse($prestation->date_demenagement);
        $delaiscarbon = $jour_j->subDays(2);
        $delai = new DateTime($delaiscarbon);
        $prestation->delai = $delai;
        $prestation->prix_total = $souscription->prix_total;
        $prestation->save();
        $client = User::where('name', $prestation->nom_client)->get()->first();
        $client->notify(new PrestationNotification($prestation));
    }
}
