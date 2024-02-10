<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Prestation;
use Illuminate\Http\Request;
use App\Models\CommentairePrestation;
use App\Http\Requests\CommentairePrestStoreRequest;
use App\Http\Requests\CommentairePrestUpdateRequest;

class CommentairePrestationController extends Controller
{
    
    public function actifCommentPrestation(Prestation $prestation){
        try{
            $actifPostsPrestations = CommentairePrestation::where('prestation_id', $prestation->id)->where('statut', 'Actif')->get();
            if($actifPostsPrestations){
                return response()->json(compact('actifPostsPrestations'), 200);
            }else{
                return response()->json([
                    'message'=>"Cet prestation n'a pas de commentaire actif"
                ]);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentairePrestStoreRequest $request, Prestation $prestation)
    {
        try{
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
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentairePrestUpdateRequest $request, CommentairePrestation $commentairePrestation)
    {
        try{
            if(auth()->user()->id == $commentairePrestation->user_id){
                $commentairePrestation->contenu = $request->contenu;
                $commentairePrestation->update();
                return response()->json([
                    'status' => 'success',
                    'Message' => 'Commentaire ajouté avec succès',
                    'Commentaire' => $commentairePrestation->contenu
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Vous ne pouvez pas effectué cet action"
                ], 404);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
       
    }
    public function activer(CommentairePrestation $commentairePrestation){
        try{
            $commentairePrestation->statut = 'Actif';
            $commentairePrestation->update();
            return response()->json([
                'status' => 'success',
                'Message' => 'Commentaire activé avec succès',
                'Commentaire' => $commentairePrestation->contenu
            ], 200);          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function desactiver(CommentairePrestation $commentairePrestation){
        try{
            if(auth()->user()->id == $commentairePrestation->user_id){
                if($commentairePrestation->statut == 'Actif'){
                    $commentairePrestation->statut = 'Inactif';
                    $commentairePrestation->update();
                    return response()->json([
                        'status' => 'success',
                        'Message' => 'Commentaire desactivé avec succès',
                        'Commentaire' => $commentairePrestation->contenu
                    ], 200);
                }else{
                    return response()->json([
                        'message'=>"Ce commentaire n'existe plus"
                    ]);
                }
                
            }else{
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à effectuer cette action"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentairePrestation $commentairePrestation)
    {
        try{
            if(auth()->user()->id == $commentairePrestation->user_id){
                if($commentairePrestation){
                    $commentairePrestation->delete();
                    return response()->json([
                        'status' => 'success',
                        'Message' => 'Commentaire supprimé avec succès',
                        'Commentaire' => $commentairePrestation->contenu
                    ], 200);
                }else{
                    return response()->json([
                        'message'=>"Ce commentaire n'existe plus"
                    ]);
                }
                
            }else{
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à effectuer cette action"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
}
