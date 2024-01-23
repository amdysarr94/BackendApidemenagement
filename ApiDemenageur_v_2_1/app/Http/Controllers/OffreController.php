<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffreStoreRequest;
use App\Http\Requests\OffreUpdateRequest;
use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
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
    public function store(OffreStoreRequest $request)
    {
        $offre = Offre::create([
            'user_id'=> auth()->user()->id,
            'nom_offre' => $request->nom_offre,
            'description_offre'=>$request->description_offre,
            'prix_offre'=>$request->prix_offre
        ]);
        return response()->json([
            'status' => 'success',
            'Message' => 'offre ajouté avec succès',
            'Infos offre' => [
                "Nom de l'offre" => $offre->nom_offre,
                "Description de l'offre" => $offre->description_offre,
                "Prix de l'offre" =>  $offre->prix_offre
                ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offre $offre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OffreUpdateRequest $request, Offre $offre)
    {
        $offre->nom_offre = $request->nom_offre;
        $offre->description_offre = $request->description_offre;
        $offre->prix_offre = $request->prix_offre;
        $offre->update();
        return response()->json([
            'status' => 'success',
            'Message' => 'offre modifié avec succès',
            'Infos offre' => [
                "Nom de l'offre" =>$offre->nom_offre,
                "Description de l'offre" =>$offre->description_offre,
                "Prix de l'offre" =>$offre->prix_offre
                ]
        ], 200);
    }
    public function activate(Offre $offre){
        $offre->statut = 'Actif';
        $offre->update();
        return response()->json([
            'status' => 'success',
            'Message' => 'offre activé avec succès',
            'Infos offre' => [
                "Nom de l'offre" =>$offre->nom_offre,
                "Description de l'offre" =>$offre->description_offre,
                "Prix de l'offre" =>$offre->prix_offre
                ]
        ], 200);
    }
    public function desactivate(Offre $offre){
        $offre->statut = 'Inactif';
        $offre->update();
        return response()->json([
            'status' => 'success',
            'Message' => 'offre désactivé avec succès',
            'Infos offre' => [
                "Nom de l'offre" =>$offre->nom_offre,
                "Description de l'offre" =>$offre->description_offre,
                "Prix  de l'offre" =>$offre->prix_offre
                ]
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
       $offre->delete();
       return response()->json([
        'status' => 'success',
        'Message' => 'offre supprimé avec succès',
        'Infos offre' => [
            "Nom de l'offre" =>$offre->nom_offre,
            "Description de l'offre" =>$offre->description_offre,
            "Prix de l'offre" =>$offre->prix_offre
            ]
    ], 200);
    }
}
