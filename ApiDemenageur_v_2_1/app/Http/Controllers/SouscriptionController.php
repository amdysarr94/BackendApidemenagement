<?php

namespace App\Http\Controllers;
use DateTime;
use Exception;
use App\Models\User;
use App\Models\Offre;
use App\Models\Souscription;
use App\Events\SouscriptionValiderEvent;
use App\Http\Requests\SouscriptionStoreRequest;
use App\Http\Requests\SouscriptionUpdateRequest;

class SouscriptionController extends Controller
{
    public function souscriptionActifOfOneCustomer(User $customer){
        try{
            $souscriptionofMovers = Souscription::where('client_id', $customer->id)->where('statut', 'Actif')->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste des souscriptions actives d'un client",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscriptions actives pour ce client"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function souscriptionInactifOfOneCustomer(User $customer){
        try{
            $souscriptionofMovers = Souscription::where('client_id', $customer->id)->where('statut', 'Inactif')->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste des souscriptions inactives d'un client",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription inactive pour ce client"
                ], 200);
            } 
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allSouscriptionOfOneCustomer(User $customer){
        try{
            $souscriptionofMovers = Souscription::where('client_id', $customer->id)->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste de toutes les souscriptions d'un client",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription pour ce client"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function souscriptionActifOfOneMover(Offre $offre){
        try{
            $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->where('statut', 'Actif')->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste des souscriptions actives d'un déménageur",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription actif pour cette offre"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function souscriptionInactifOfOneMover(Offre $offre){
        try{
            $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->where('statut', 'Inactif')->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste des souscriptions inactives d'un déménageur",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription inactives pour cette offre"
                ], 200);
            } 
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allSouscriptionOfOneMover(Offre $offre){
        try{
            $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->get();
            if($souscriptionofMovers){
                return response()->json([
                    'status'=>"success",
                    'message'=>"Liste de toutes souscriptions  d'un déménageur",
                    'données'=>$souscriptionofMovers
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscriptions pour cette offre"
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(SouscriptionStoreRequest $request, Offre $offre)
    {
        try{
            $souscription = new Souscription();
            $souscription->client_id = auth()->user()->id;
            $souscription->offre_id = $offre->id;
            $souscription->nom_offre = $offre->nom_offre;
            $souscription->nom_client = auth()->user()->name;
            $souscription->prix_total = $offre->prix_offre;
            $souscription->adresse_actuelle = $request->adresse_actuelle;
            $souscription->nouvelle_adresse = $request->nouvelle_adresse;
            $souscription->description = $offre->description_offre;
            $currentDay = new DateTime();
            $currentDayDeux = new DateTime();
            $jour_j = new DateTime($request->date_demenagement);
            $diff = $jour_j->diff($currentDayDeux);
            $limiteMax = $currentDayDeux->modify('+60 days');
            if($diff->days >= 10 && $jour_j > $currentDay && $jour_j <= $limiteMax){
                $souscription->date_demenagement = $request->date_demenagement;
                $souscription->save();
                return response()->json([
                    "message"=>"Souscription enregistrée avec succès",
                    "Informations de la souscription"=> [
                        "Nom de l'offre" => $souscription->nom_offre,
                        "Description de l'offre" => $souscription->description,
                        "Nom du client" => $souscription->nom_client
                    ]
                ], 201);
            }else{
                return response()->json([
                    'message' => "Votre date de déménagement doit être à plus de 10 jours et 60 jours de la date d'aujourd'hui"
                ]);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SouscriptionUpdateRequest $request, Souscription $souscription)
    {
        try{
            if(auth()->user()->id == $souscription->client_id && $souscription->etat == 'En cours'){
                $souscription->adresse_actuelle = $request->adresse_actuelle;
                $souscription->nouvelle_adresse = $request->nouvelle_adresse;
                $currentDay = new DateTime();
                $jour_j = new DateTime($request->date_demenagement);
                $diff = $jour_j->diff($currentDay);
                $currentDayTwo = new DateTime();
                $limiteMax = $currentDayTwo->modify('+60 days');
                // dd($limiteMax, $jour_j, );
                if($diff->days >= 10 && $jour_j > $currentDay && $jour_j <= $limiteMax){
                    $souscription->date_demenagement = $request->date_demenagement;
                    $souscription->update();
                    return response()->json([
                        "message"=>"Souscription modifiée avec succès",
                        "Informations de la souscription"=> [
                            "Nom de l'offre" => $souscription->nom_offre,
                            "Description de l'offre" => $souscription->description,
                            "Nom du client" => $souscription->nom_client
                        ]
                    ], 200);
                }else{
                    return response()->json([
                        'message' => "Votre date de déménagement doit être à plus de 10 jours et 60 jours de la date d'aujourd'hui"
                    ]);
                }
                
            }else{
                return response()->json([
                    'message'=> "Vous n'êtes pas autorisé à effectuer cette action"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function valider(Souscription $souscription){
        try{
            $user_id= auth()->user()->id;
            $nom_offre = $souscription->nom_offre;
            $souscription_id = $souscription->id;
            $souscription = Souscription::whereHas('offre', function ($query) use ($user_id, $nom_offre) {
                $query->where('user_id', $user_id)
                      ->where('nom_offre', $nom_offre);
            })->where('id', $souscription_id)->get()->first();
            if($souscription){
                    if(auth()->user()->role == 'Demenageur' && $souscription->etat == 'En cours'){
                        event(new SouscriptionValiderEvent($souscription->id));
                        $souscription->etat == 'Valide';
                        $souscription->update();
                        //Les autres souscriptions du client pour la même date de déménagement
                        $autresSouscriptions = Souscription::where('nom_client', $souscription->nom_client)
                                                           ->where('id', '!=', $souscription->id)
                                                           ->where('date_demenagement', '=', $souscription->date_demenagement)
                                                           ->where('etat', 'En cours')
                                                           ->get();
                        foreach($autresSouscriptions as $autreSouscription){
                            $autreSouscription->etat = 'Refuse';
                            $autreSouscription->statut = 'Inactif';
                            $autreSouscription->update();
                        }
                        return response()->json([
                            "message"=>"Souscription validée avec succès",
                            "Informations de la souscription"=> [
                                "Nom de l'offre" => $souscription->nom_offre,
                                "Description de l'offre" => $souscription->description,
                                "Nom du client" => $souscription->nom_client
                            ]
                        ], 200);
                        } else {
                            return response()->json([
                                "message"=>"Accès refusé !",
                            ], 403);
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
    public function refuser(Souscription $souscription){
        try{
            $user_id= auth()->user()->id;
            $nom_offre = $souscription->nom_offre;
            $souscription_id = $souscription->id;
            $souscription = Souscription::whereHas('offre', function ($query) use ($user_id, $nom_offre) {
                $query->where('user_id', $user_id)
                      ->where('nom_offre', $nom_offre);
            })->where('id', $souscription_id)->get()->first();
            
            if($souscription && auth()->user()->role == 'Demenageur'){
                    $souscription->etat = 'Refuse';
                    $souscription->statut = 'Inactif';
                    $souscription->update();
                    return response()->json([
                        'message'=>"Souscription refusé avec succès."
                    ]);
            }else{
                    return response()->json([
                        'message'=>"Vous ne pouvez effectué cet action."
                    ]);
                }
                     
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function activate(Souscription $souscription){
        try{
            if($souscription->statut == 'Inactif'){
                $souscription->statut = 'Actif';
                $souscription->update();
                return response()->json([
                    "message"=>"Souscription activée avec succès",
                    "Informations de la souscription"=> [
                        "Nom de l'offre" => $souscription->nom_offre,
                        "Description de l'offre" => $souscription->description,
                        "Nom du client" => $souscription->nom_client
                    ]
                ], 200);
            }else{
                return response()->json([
                    "message"=>"Cette souscription est déjà activé",
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function desactivate(Souscription $souscription){
        try{
            if(auth()->user()->id == $souscription->client_id && $souscription->etat == 'En cours'){
                if($souscription->statut == 'Actif'){
                    $souscription->statut = 'Inactif';
                    $souscription->update();
                    return response()->json([
                        "message"=>"Souscription résiliée avec succès",
                        "Informations de la souscription"=> [
                            "Nom de l'offre" => $souscription->nom_offre,
                            "Description de l'offre" => $souscription->description,
                            "Nom du client" => $souscription->nom_client
                        ]
                    ], 200);
                }else{
                    return response()->json([
                        "message"=>"Cette souscription est déjà résilié",
                    ], 200);
                }
                
            }else{
                return response()->json([
                    "message"=>"Impossible d'effectué cet action",
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Souscription $souscription)
    {
        try{
            $souscription->delete();
            return response()->json([
                "message"=>"Souscription supprimée avec succès",
                "Informations de la souscription"=> [
                    "Nom de l'offre" => $souscription->nom_offre,
                    "Description de l'offre" => $souscription->description,
                    "Nom du client" => $souscription->nom_client
                ]
            ], 200);          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
