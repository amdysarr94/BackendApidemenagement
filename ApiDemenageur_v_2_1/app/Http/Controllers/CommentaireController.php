<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentaireController extends Controller
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
    public function store(CommentStoreRequest $request, Article $article)
    {
        $comment = new Commentaire();
        $comment->user_id = auth()->user()->id;
        $comment->article_id = $article->id;
        $comment->contenu = $request->contenu;
        $comment->save();
        return response()->json([
            'status' => 'success',
            'Message' => 'Commentaire ajouté avec succès',
            'Commentaire' => $comment->contenu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commentaire $commentaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commentaire $commentaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Commentaire $commentaire)
    {
        if(auth()->user()->id = $commentaire->user_id){
            $commentaire->contenu = $request->contenu;
            $commentaire->update();
            return response()->json([
            'status' => 'success',
            'Message' => 'Commentaire modifié avec succès',
            'Commentaire' => $commentaire->contenu
            ], 200);
        }
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commentaire $commentaire)
    {
        if(auth()->user()->id = $commentaire->user_id || auth()->user()->role == 'Admin'){
            $commentaire->delete();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire supprimé avec succès',
                'Commentaire' => $commentaire->contenu
            ], 200);
        }
        
    }
    public function activate(Commentaire $commentaire){
        if(auth()->user()->role == 'Admin'){
            $commentaire->statut = "Actif";
            $commentaire->update();
            return response()->json([
            'status' => 'success',
            'Message' => 'Commentaire activé avec succès',
            'Commentaire' => $commentaire->contenu
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'Message' => "Vous n'êtes pas autorisé à effectuer cet action"
            ]);
        }
        
    }
    public function desactivate(Commentaire $commentaire){
        if(auth()->user()->id = $commentaire->user_id || auth()->user()->role == 'Admin'){
            $commentaire->statut = "Inactif";
            $commentaire->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire désactivé avec succès',
                'Commentaire' => $commentaire->contenu
            ], 200);
        }
        
    }
}
