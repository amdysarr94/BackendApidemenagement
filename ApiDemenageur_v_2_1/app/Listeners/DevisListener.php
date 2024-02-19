<?php

namespace App\Listeners;
use DateTime;
use App\Models\User;
use App\Models\Devis;
use App\Models\Prestation;
use Illuminate\Support\Carbon;
use App\Models\InformationsSupp;
use App\Events\DevisValiderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PrestationNotification;
use App\Notifications\PrestationDevisNotification;

class DevisListener
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
    public function handle(DevisValiderEvent $event): void
    {
        //devis->demenageur_id (Users)
        $devisId = $event->devisId;
        $devis = Devis::findOrfail($devisId);
        $prestation = new Prestation();
        $prestation->nom_client = $devis->nom_client;
        $clientid = User::where('name', $devis->nom_client)->get()->first()->id;
        $demenageurInfos = InformationsSupp::where('user_id', $devis->demenageur_id)->get()->first();
        $nom_demenageur = $demenageurInfos->nom_entreprise;
        $prestation->nom_entreprise = $nom_demenageur;
        $prestation->prix_total = $devis->prix_total;
        $prestation->adresse_actuelle = $devis->adresse_actuelle;
        $prestation->nouvelle_adresse = $devis->nouvelle_adresse;
        $prestation->description = $devis->description;
        //gestion des dates 
        $prestation->date_demenagement = $devis->date_demenagement;
        $jour_j  = Carbon::parse($prestation->date_demenagement);
        $delaiscarbon = $jour_j->subDays(2);
        $delai = new DateTime($delaiscarbon);
        $prestation->delai = $delai;
        $prestation->prix_total = $devis->prix_total;
        $prestation->client_id = $clientid;
        $prestation->save();
        $client = User::where('name', $prestation->nom_client)->get()->first();
        $client->notify(new PrestationNotification($prestation));
    }
}
