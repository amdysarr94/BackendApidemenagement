<?php

namespace App\Listeners;
use DateTime;
use App\Models\Prestation;
use App\Models\Souscription;
use Illuminate\Support\Carbon;
use App\Events\SouscriptionValiderEvent;
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
        $souscriptionId = $event->souscriptionId;
        $souscription = Souscription::findOrFail($souscriptionId);
        $prestation = new Prestation();
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
    }
}
