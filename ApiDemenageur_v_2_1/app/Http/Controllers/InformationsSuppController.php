<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InformationsSupp;
use App\Http\Requests\InformationsSuppStoreRequest;
use App\Http\Requests\InformationsSuppUpdateRequest;

class InformationsSuppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allInformationsSuppOfAllMovers(){
        $informationsSupps = InformationsSupp::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Toutes les informations des entreprises de déménagement',
            'data' => $informationsSupps
        ]);
    }
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
        try{
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
                    "Nom de l'entreprise" => $informationsSupp->nom_entreprise,
                    "Présentation de l'entreprise" => $informationsSupp->presentation,
                    ]
            ], 201);          
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $demenageur)
    {
        try{
            $informationsSupp = InformationsSupp::where('user_id', $demenageur->id)->get();
            if($informationsSupp){
                return response()->json(compact('informationsSupp'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce déménageur n'a pas d'informations supplémentaires"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }
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
        try{
            if(auth()->user()->id == $informationsSupp->user_id){
                $informationsSupp->nom_entreprise  = $request->nom_entreprise;  
                $informationsSupp->presentation = $request->presentation; 
                $informationsSupp->NINEA = $request->NINEA; 
                $informationsSupp->forme_juridique = $request->forme_juridique; 
                $informationsSupp->annee_creation = $request->annee_creation; 
                $informationsSupp->update();
                return response()->json([
                    'status' => 'success',
                    'Message' => "Informations de l'entreprise mises-à-jour avec succès",
                    'Infos offre' => [
                        "Nom de l'entreprise" => $informationsSupp->nom_entreprise,
                        "Présentation de l'entreprise" => $informationsSupp->presentation,
                        ]
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }

        
        
    }

    /**
     * Activer une information supplémentaires.
     */
    public function activate(InformationsSupp $informationsSupp){
        try{
            if($informationsSupp->statut == 'Inactif'){
                $informationsSupp->statut = 'Actif';
                $informationsSupp->update();
                return response()->json([
                    'status' => 'success',
                    'Message' => "Informations de l'entreprise activées avec succès",
                    'Infos offre' => [
                        "Nom de l'entreprise" => $informationsSupp->nom_entreprise,
                        "Présentation de l'entreprise" => $informationsSupp->presentation,
                        ]
                ], 200);
            }else{
                return response()->json([
                    'message'=>"Informations de l'entreprise déjà actif"
                ]);
            }          
        }catch(Exception $e){
            return response()->json($e);
        }  
    }

    /**
     * Désactiver une information supplémentaires.
     */
    public function desactivate(InformationsSupp $informationsSupp){
        try{
            if(auth()->user()->id == $informationsSupp->user_id ){
                if($informationsSupp->statut == 'Actif'){
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
                }else{
                    return response()->json([
                        'message'=> "Ces  informations n'existent plus"
                    ]);
                }
                
            }else{
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à effectuer cette action"
                ]);
            }          
        }catch(Exception $e){
            return response()->json($e);
        }

        
        
    }


    /**
     * Supprimer une information supplémentaires.
     */
    public function destroy(InformationsSupp $informationsSupp)
    {
        try{
            if($informationsSupp){
                if(auth()->user()->id = $informationsSupp->user_id){
                    $informationsSupp->delete();
                    return response()->json([
                        'status' => 'success',
                        'Message' => "Informations de l'entreprise supprimées avec succès",
                        'Infos offre' => [
                            "Nom de l'entreprise" => $informationsSupp->nom_entreprise,
                            "Présentation de l'entreprise" => $informationsSupp->presentation,
                            ]
                    ], 200);
                }else{
                    return response()->json([
                        'message' => "Vous n'êtes pas autorisé à effectué cet action"
                    ], 403);
                }
            }else{
                return response()->json([
                    'message' => "Ces informations n'existe plus"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }  
    }
}
