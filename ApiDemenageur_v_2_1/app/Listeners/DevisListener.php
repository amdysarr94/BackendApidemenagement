<?php

namespace App\Listeners;
use DateTime;
use App\Models\Devis;
use App\Events\DevisValiderEvent;
use App\Models\Prestation;
use Illuminate\Support\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $devisId = $event->devisId;
        $devis = Devis::findOrfail($devisId);
        $prestation = new Prestation();
        $prestation->nom_client = $devis->nom_client;
        $prestation->prix_total = $devis->prix_total;
        $prestation->adresse_actuelle = $devis->adresse_actuelle;
        $prestation->nouvvelle_adresse = $devis->nouvvelle_adresse;
        $prestation->description = $devis->description;
        //gestion des dates 
        $prestation->date_demenagement = $devis->date_demenagement;
        $jour_j  = Carbon::parse($prestation->date_demenagement);
        $delaiscarbon = $jour_j->subDays(2);
        $delai = new DateTime($delaiscarbon);
        $prestation->delai = $delai;
        $prestation->prix_total = $devis->prix_total;
        $prestation->save();

    }
}
