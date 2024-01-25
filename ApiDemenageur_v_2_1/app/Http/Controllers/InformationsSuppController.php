<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformationsSupp;
use App\Http\Requests\InformationsSuppStoreRequest;
use App\Http\Requests\InformationsSuppUpdateRequest;

class InformationsSuppController extends Controller
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
    public function store(InformationsSuppStoreRequest $request)
    {
        $informationsSupp = InformationsSupp::create([
            'user_id' => auth()->user()->id,
            'presentation' => $request->presentation,
            'NINEA' => $request->NINEA,
            'nom_entreprise' => $request->nom_entreprise,
            'forme_juridique' => $request->forme_juridique,
            'annee_creation'  => $request->annee_creation,
        ]);
        return response()->json([
            'status' => 'success',
            'Message' => "Informations de l'entreprise ajoutées avec succès",
            'Infos offre' => [
                "Nom de l'entreprise" => $informationsSupp->presentation,
                "Présentation de l'entreprise" => $informationsSupp->nom_entreprise,
                ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InformationsSupp $informationsSupp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InformationsSupp $informationsSupp)
    {
        //
    }

    /**
     * Modifier une information supplémentaire spécifique.
     */
    public function update(InformationsSuppUpdateRequest $request, InformationsSupp $informationsSupp)
    {
        if(auth()->user()->id == $informationsSupp->user_id){
            $informationsSupp = $request->nom_entreprise ;  
            $informationsSupp = $request->presentation ; 
            $informationsSupp = $request->NINEA; 
            $informationsSupp = $request->forme_juridique; 
            $informationsSupp = $request->annee_creation; 
            $informationsSupp->update();
            return response()->json([
                'status' => 'success',
                'Message' => "Informations de l'entreprise mises-à-jour avec succès",
                'Infos offre' => [
                    "Nom de l'entreprise" => $informationsSupp->presentation,
                    "Présentation de l'entreprise" => $informationsSupp->nom_entreprise,
                    ]
            ], 200);
        }
        
    }

    /**
     * Activer une information supplémentaires.
     */
    public function activate(InformationsSupp $informationsSupp){
        $informationsSupp->statut = 'Actif';
        $informationsSupp->update();
        return response()->json([
            'status' => 'success',
            'Message' => "Informations de l'entreprise activées avec succès",
            'Infos offre' => [
                "Nom de l'entreprise" => $informationsSupp->presentation,
                "Présentation de l'entreprise" => $informationsSupp->nom_entreprise,
                ]
        ], 200);
    }

    /**
     * Désactiver une information supplémentaires.
     */
    public function desactivate(InformationsSupp $informationsSupp){
        if(auth()->user()->id == $informationsSupp->user_id){
            $informationsSupp->statut = 'Inactif';
            $informationsSupp->update();
            return response()->json([
                'status' => 'success',
                'Message' => "Informations de l'entreprise désactivées avec succès",
                'Infos offre' => [
                    "Nom de l'entreprise" => $informationsSupp->presentation,
                    "Présentation de l'entreprise" => $informationsSupp->nom_entreprise,
                    ]
            ], 200);
        }
        
    }


    /**
     * Supprimer une information supplémentaires.
     */
    public function destroy(InformationsSupp $informationsSupp)
    {
        if(auth()->user()->id = $informationsSupp->user_id){
            $informationsSupp->delete();
            return response()->json([
                'status' => 'success',
                'Message' => "Informations de l'entreprise supprimées avec succès",
                'Infos offre' => [
                    "Nom de l'entreprise" => $informationsSupp->presentation,
                    "Présentation de l'entreprise" => $informationsSupp->nom_entreprise,
                    ]
            ], 200);
        }
        
    }
}
