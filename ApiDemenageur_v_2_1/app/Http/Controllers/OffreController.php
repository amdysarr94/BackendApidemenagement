<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Offre;
use App\Models\Prestation;
use Illuminate\Http\Request;
use App\Http\Requests\OffreStoreRequest;
use App\Http\Requests\OffreUpdateRequest;

class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $actifoffers = Offre::where('statut', 'Actif')->get();
            return response()->json([
                'status'=>"success",
                'message'=>"La liste de toutes les offres actives",
                'data'=>$actifoffers
            ], 200);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function inactifOffers(){
        try{
            $inactifoffers = Offre::where('statut', 'Inactif')->get();
            return response()->json([
                'status'=>"success",
                'message'=>"La liste de toutes les offres inactives",
                'data'=>$inactifoffers
            ], 200);          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allActifOfferOfOneMover(User $demenageur){
        try{
            $actifoffers = Offre::where('statut', 'Actif')->where('user_id', $demenageur->id)->get();
            if($actifoffers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"La liste des offres actives d'un déménageur",
                    'data'=>$actifoffers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Ce demenageur n'a pas d'offres actives"
                ]);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function allInactifOfferOfOneMover(User $demenageur){
        try{
            $inactifoffers = Offre::where('statut', 'Inactif')->where('user_id', $demenageur->id)->get();
            if($inactifoffers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"La liste des offres inactives d'un déménageur",
                    'data'=>$inactifoffers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Ce demenageur n'a pas d'offres inactives"
                ]);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(OffreStoreRequest $request)
    {   
        try{
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
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        //
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(OffreUpdateRequest $request, Offre $offre)
    {
        try{
            if(auth()->user()->id == $offre->user_id){
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
            }else{
                return response()->json([
                    "message"=>"Accès refusé !",
                ], 403);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function activate(Offre $offre){
        try{
            if( $offre->statut == 'Inactif'){
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
            }else{
                return response()->json([
                    "message"=>"Cette offre est déjà actif",
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function desactivate(Offre $offre){
        try{
            if($offre->statut == 'Actif'){
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
            }else{
                return response()->json([
                    'message'=> "Cette offre est déjà supprimé"
                ], 200);
            }
                       
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        try{
            if($offre){
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
            }else{
                return response()->json([
                    'message'=> "Cette offre est déjà supprimé"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
       
    }
    public function chatwhatsapp(User $demenageur){
        try{
            $whatsappPhone = $demenageur->telephone;
            $whatsappPhone = substr($whatsappPhone, 4);
            $whatsappUrl = "https://api.whatsapp.com/send?phone=$whatsappPhone";
            return redirect()->to($whatsappUrl);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }      
    }
}
