<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentairePrestStoreRequest;
use App\Http\Requests\CommentairePrestUpdateRequest;
use App\Models\CommentairePrestation;
use App\Models\Prestation;
use Illuminate\Http\Request;

class CommentairePrestationController extends Controller
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
    public function store(CommentairePrestStoreRequest $request, Prestation $prestation)
    {
        $commentairePrestation = new CommentairePrestation();
        $commentairePrestation->user_id = auth()->user()->id;
        $commentairePrestation->prestation_id = $prestation->id;
        $commentairePrestation->contenu = $request->contenu;
        $commentairePrestation->save();
        return response()->json([
            'status' => 'success',
            'Message' => 'Commentaire ajouté avec succès',
            'Commentaire' => $commentairePrestation->contenu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentairePrestation $commentairePrestation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommentairePrestation $commentairePrestation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentairePrestUpdateRequest $request, CommentairePrestation $commentairePrestation)
    {
        if(auth()->user()->id == $commentairePrestation->user_id){
            $commentairePrestation->contenu = $request->contenu;
            $commentairePrestation->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire ajouté avec succès',
                'Commentaire' => $commentairePrestation->contenu
            ], 200);
        }else{

        }
       
    }
    public function activer(CommentairePrestation $commentairePrestation){

        $commentairePrestation->statut = 'Actif';
        $commentairePrestation->update();
        return response()->json([
            'status' => 'success',
            'Message' => 'Commentaire activé avec succès',
            'Commentaire' => $commentairePrestation->contenu
        ], 200);
    }
    public function desactiver(CommentairePrestation $commentairePrestation){
        if(auth()->user()->id == $commentairePrestation->user_id){
            $commentairePrestation->statut = 'Inactif';
            $commentairePrestation->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire desactivé avec succès',
                'Commentaire' => $commentairePrestation->contenu
            ], 200);
        }else{

        }
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentairePrestation $commentairePrestation)
    {
        if(auth()->user()->id == $commentairePrestation->user_id){
            $commentairePrestation->delete();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire supprimé avec succès',
                'Commentaire' => $commentairePrestation->contenu
            ], 200);
        }else{
            
        }
        
    }
}
