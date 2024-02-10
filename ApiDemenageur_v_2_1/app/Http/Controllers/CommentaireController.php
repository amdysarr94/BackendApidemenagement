<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Article;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentaireController extends Controller
{
    
    public function activeCommentPost(Article $article){
        try{
            $comments = Commentaire::where('article_id', $article->id)->where('statut', 'Actif')->get();
            if($comments){
                return response()->json(compact('comments'), 200);
            }else{
                return response()->json([
                    'message'=>"Cet article n'a pas de commentaire actif."
                ]);
            }       
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function inactiveCommentPost(Article $article){
        try{
            $comments = Commentaire::where('article_id', $article->id)->where('statut', 'Inactif')->get();
            if($comments){
                return response()->json(compact('comments'), 200);
            }else{
                return response()->json([
                    'message'=>"Cet article n'a pas de commentaire inactif."
                ]);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, Article $article)
    {
        try{
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
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Commentaire $commentaire)
    {
        try{
            if(auth()->user()->id = $commentaire->user_id){
                $commentaire->contenu = $request->contenu;
                $commentaire->update();
                return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire modifié avec succès',
                'Commentaire' => $commentaire->contenu
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commentaire $commentaire)
    {
        try{
            if(auth()->user()->id = $commentaire->user_id || auth()->user()->role == 'Admin'){
                $commentaire->delete();
                return response()->json([
                    'status' => 'success',
                    'Message' => 'Commentaire supprimé avec succès',
                    'Commentaire' => $commentaire->contenu
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function activate(Commentaire $commentaire){
        try{
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
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function desactivate(Commentaire $commentaire){
        try{
            if(auth()->user()->id = $commentaire->user_id || auth()->user()->role == 'Admin'){
                $commentaire->statut = "Inactif";
                $commentaire->update();
                return response()->json([
                    'status' => 'success',
                    'Message' => 'Commentaire désactivé avec succès',
                    'Commentaire' => $commentaire->contenu
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
}
