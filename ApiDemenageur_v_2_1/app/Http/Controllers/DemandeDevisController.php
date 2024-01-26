<?php

namespace App\Http\Controllers;

use App\Models\DemandeDevis;
use Illuminate\Http\Request;
use App\Http\Requests\DemandeDevisStoreRequest;
use App\Http\Requests\DemandeDevisUpdateRequest;
use Illuminate\Support\Carbon;
use DateTime;

class DemandeDevisController extends Controller
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
    public function store(DemandeDevisStoreRequest $request)
    {
        $demandedevis = new DemandeDevis();
        $demandedevis->client_id = auth()->user()->id;
        $demandedevis->nom_client = auth()->user()->name;
        $demandedevis->nom_entreprise = $request->nom_entreprise;
        $demandedevis->adresse_actuelle = $request->adresse_actuelle;
        $demandedevis->nouvelle_adresse = $request->nouvelle_adresse;
        $demandedevis->informations_bagages = $request->informations_bagages;
        // $demandedevis->date_demenagement = $request->date_demenagement;
        $date = new DateTime($request->date_demenagement);
        $jour_j = Carbon::parse($date);
        $interval = $jour_j->subDays(10);
        $currentDay = Carbon::now();
        $dateLimiteMax = $currentDay->addDays(40);
        dd(
            'ok',
            $currentDay, 
        );
        if($interval->gt($currentDay) ){
            // if($jour_j->lte($dateLimiteMax)){
            //     dd('ouf');
            // }
            dd(
                'ok',
                $currentDay,
                $interval->isPast() 
            );
            $demandedevis->date_demenagement = $request->date_demenagement;
            $demandedevis->save();
            return response()->json([
                'status' => 'success',
                'Message' => 'Demande de devis envoyé avec succès',
                'Infos demande de devis' => [
                    $demandedevis->nom_client,
                    $demandedevis->adresse_actuelle,
                    $demandedevis->nouvelle_adresse
                    ]
            ], 201);
        }else{
            return response()->json([
                'message' => "Votre date de déménagement doit être à plus de 10 jours de la date d'aujourd'hui"
            ]);
            dd("c'est passé");
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeDevis $demandeDevis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeDevis $demandeDevis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandeDevisUpdateRequest $request, DemandeDevis $demandeDevis)
    {
        if(auth()->user()->id == $demandeDevis->client_id){
            $demandeDevis->nom_entreprise = $request->nom_entreprise;
            $demandeDevis->adresse_actuelle = $request->adresse_actuelle;
            $demandeDevis->nouvelle_adresse = $request->nouvelle_adresse;
            $demandeDevis->informations_bagages = $request->informations_bagages;
            $demandeDevis->date_demenagement = $request->date_demenagement;
            $demandeDevis->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Demande de devis modifié avec succès',
                'Infos demande de devis' => [
                    $demandeDevis->nom_client,
                    $demandeDevis->adresse_actuelle,
                    $demandeDevis->nouvelle_adresse
                    ]
            ], 200);
        }
    }
    public function desactivate(DemandeDevis $demandeDevis){
        if(auth()->user()->id == $demandeDevis->client_id){
            $demandeDevis->statut = 'Inactif';
            $demandeDevis->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Demande de devis supprimé avec succès',
                'Infos demande de devis' => [
                    $demandeDevis->nom_client,
                    $demandeDevis->adresse_actuelle,
                    $demandeDevis->nouvelle_adresse
                    ]
            ], 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeDevis $demandeDevis)
    {
        if(auth()->user()->role == 'Admin'){
            $demandeDevis->delete();
            return response()->json([
                'status' => 'success',
                'Message' => 'Demande de devis Supprimé avec succès',
                'Infos demande de devis' => [
                    $demandeDevis->nom_client,
                    $demandeDevis->adresse_actuelle,
                    $demandeDevis->nouvelle_adresse
                    ]
            ], 200);
        }
    }
}
