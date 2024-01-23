<?php

namespace App\Http\Controllers;

use App\Events\DevisValiderEvent;
use App\Models\Devis;
use Illuminate\Http\Request;
use App\Http\Requests\DevisStoreRequest;
use App\Http\Requests\DevisUpdateRequest;
use App\Models\DemandeDevis;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevisStoreRequest $request, DemandeDevis $demandeDevis)
    {
        $devis = new Devis();
        $devis->demenageur_id = auth()->user()->id;
        $devis->demande_devis_id = $demandeDevis->id;
        $devis->nom_client = $demandeDevis->nom_client;
        $devis->date_demenagement = $demandeDevis->date_demenagement;
        $devis->adresse_actuelle = $demandeDevis->adresse_actuelle;
        $devis->nouvvelle_adresse = $demandeDevis->nouvelle_adresse;
        $devis->prix_total = $request->prix_total;
        $devis->description = $request->description;
        $devis->save();
        return response()->json([
            "message"=>"Devis envoyé avec succès",
            "Informations du devis"=> [
                'Nom du client' => $devis->nom_client,
                'Prix du déménagement'=> $devis->prix_total,
                'Description' => $devis->description,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Devis $devis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devis $devis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevisUpdateRequest $request, Devis $devis)
    {
        $devis->prix_total = $request->prix_total;
        $devis->description = $request->description;
        $devis->update();
        return response()->json([
            "message"=>"Devis mis-à-jour avec succès",
            "Informations du devis"=> [
                'Nom du client' => $devis->nom_client,
                'Prix du déménagement'=> $devis->prix_total,
                'Description' => $devis->description,
             ]
        ], 200);
    }
    public function activate(Devis $devis){
        $devis->statut = 'Actif';
        return response()->json([
            "message"=>"Devis activé avec succès",
            "Informations du devis"=> [
                'Nom du client' => $devis->nom_client,
                'Prix du déménagement'=> $devis->prix_total,
                'Adresse actuelle du client' => $devis->adresse_actuelle,
                'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
            ]
        ], 200);
    }
    public function desactivate(Devis $devis){
        $devis->statut = 'Inactif';
        return response()->json([
            "message"=>"Devis désactivé avec succès",
            "Informations du devis"=> [
                'Nom du client' => $devis->nom_client,
                'Prix du déménagement'=> $devis->prix_total,
                'Adresse actuelle du client' => $devis->adresse_actuelle,
                'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
            ]
        ], 200);
    }
    public function valider(Devis $devis){
        if(auth()->user()->name == $devis->nom_client){
            event(new DevisValiderEvent($devis->id));
            return response()->json([
                "message"=>"Devis validé avec succès",
                "Informations du devis"=> [
                    'Nom du client' => $devis->nom_client,
                    'Prix du déménagement'=> $devis->prix_total,
                    'Adresse actuelle du client' => $devis->adresse_actuelle,
                    'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
                ]
            ], 200);
        }else{
            return response()->json([
                "message"=>"Accès refusé !",
            ], 403);
        }
        
    }
    public function refuser(Devis $devis){

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devis $devis)
    {
        $devis->delete();
        return response()->json([
            "message"=>"Devis supprimé avec succès",
            "Informations du devis"=> [
                'Nom du client' => $devis->nom_client,
                'Prix du déménagement'=> $devis->prix_total,
                'Adresse actuelle du client' => $devis->adresse_actuelle,
                'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
            ]
        ], 200);
    }
}
