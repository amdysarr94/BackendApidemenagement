<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Devis;
use App\Models\DemandeDevis;
use Illuminate\Http\Request;
use App\Events\DevisValiderEvent;
use App\Http\Requests\DevisStoreRequest;
use App\Http\Requests\DevisUpdateRequest;
use App\Notifications\DevisSendNotification;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function devisActifOfOneCustomer(User $customer){
        try{
            $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->where('statut', 'Actif')->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce client n'a pas de devis actifs"
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function devisInactifOfOneCustomer(User $customer){
        try{
            $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->where('statut', 'Inactif')->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce client n'a pas de devis inactifs"
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function allDevisOfOneCustomer(User $customer){
        try{
            $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce client n'a pas reçu de devis."
                ]);
            }          
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function devisActifOfOneMover(User $demenageur){
        try{
            $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->where('statut', 'Actif')->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce demenageur n'a pas de devis actifs"
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function devisInactifOfOneMover(User $demenageur){
        try{
            $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->where('statut', 'Inactif')->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce demenageur n'a pas de devis inactifs"
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function AllDevisOfOneMover(User $demenageur){
        try{
            $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->get();
            if($devisOfMovers){
                return response()->json(compact('devisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce déménageur n'a pas de devis."
                ]);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevisStoreRequest $request, DemandeDevis $demandeDevis)
    {
        try{
            if($demandeDevis->statut == 'Actif'){
                $devis = new Devis();
                $devis->demenageur_id = auth()->user()->id;
                $devis->demande_devis_id = $demandeDevis->id;
                $devis->nom_client = $demandeDevis->nom_client;
                $devis->date_demenagement = $demandeDevis->date_demenagement;
                $devis->adresse_actuelle = $demandeDevis->adresse_actuelle;
                $devis->nouvelle_adresse = $demandeDevis->nouvelle_adresse;
                $devis->prix_total = $request->prix_total;
                $devis->description = $request->description;
                $devis->save();
                $client = User::where('name', $devis->nom_client)->get()->first();
                $client->notify(new DevisSendNotification($devis));
                return response()->json([
                    "message"=>"Devis envoyé avec succès",
                    "Informations du devis"=> [
                        'Nom du client' => $devis->nom_client,
                        'Prix du déménagement'=> $devis->prix_total,
                        'Description' => $devis->description,
                    ]
                ], 201);
                
            }else{
                return response()->json([
                    'Message' => "Cette demande a été supprimée"
                ]);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        } 
        
        
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(DevisUpdateRequest $request, Devis $devis)
    {
        try{
            if(auth()->user()->id == $devis->demenageur_id && $devis->etat == 'En cours'){
                $devis->prix_total = $request->prix_total;
                $devis->description = $request->description;
                $devis->update();
                return response()->json([
                    "message"=>"Devis mis-à-jour avec succès",
                    "Informations du devis"=> [
                        'Nom du client' => $devis->nom_client,
                        'Prix du déménagement'=> $devis->prix_total,
                        'Description' => $devis->description,
                     ]
                ], 200);
            }else{
                return response()->json([
                    'Message' => "Vous ne pouvez modifier ce devis"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function activate(Devis $devis){
        try{
            if(auth()->user()->id == $devis->demenageur_id){
                if($devis->statut == 'Inactif'){
                    $devis->statut = 'Actif';
                return response()->json([
                    "message"=>"Devis activé avec succès",
                    "Informations du devis"=> [
                        'Nom du client' => $devis->nom_client,
                        'Prix du déménagement'=> $devis->prix_total,
                        'Adresse actuelle du client' => $devis->adresse_actuelle,
                        'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
                    ]
                ], 200);
                    }else{
                        return response()->json([
                        'Message' => "Vous ne pouvez modifier ce devis"
                        ], 403);
                }
            }else{
                return response()->json([
                    'Message' => "Ce devis est déjà actif"
                    ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
    public function desactivate(Devis $devis){
        try{
            if(auth()->user()->id == $devis->demenageur_id && $devis->etat == 'En cours'){
                if( $devis->statut = 'Actif'){
                    $devis->statut = 'Inactif';
                    return response()->json([
                        "message"=>"Devis désactivé avec succès",
                        "Informations du devis"=> [
                            'Nom du client' => $devis->nom_client,
                            'Prix du déménagement'=> $devis->prix_total,
                            'Adresse actuelle du client' => $devis->adresse_actuelle,
                            'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
                            ]
                            ], 200);
                    }else{
                        return response()->json([
                            'Message' => "Vous ne pouvez modifier ce devis"
                            ], 403);
                }
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

         
    }
    public function valider(Devis $devis){
         try{
            if($devis->statut == 'Actif' && $devis->etat == 'En cours'){
                if(auth()->user()->name == $devis->nom_client){
                    event(new DevisValiderEvent($devis->id));
                    $devis->etat == 'Valide';
                    $devis->update();
                    return response()->json([
                        "message"=>"Devis validé avec succès",
                        "Informations du devis"=> [
                            'Nom du client' => $devis->nom_client,
                            'Prix du déménagement'=> $devis->prix_total,
                            'Adresse actuelle du client' => $devis->adresse_actuelle,
                            'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
                        ]
                    ], 200);
                    }else{
                    return response()->json([
                        "message"=>"Accès refusé !",
                    ], 403);
                }
                }else{
                return response()->json([
                    "message"=>"Ce devis n'existe plus !",
                ], 404);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
        
    }
    public function refuser(Devis $devis){
         try{
            if($devis->statut == 'Actif' && $devis->etat == 'En cours'){
                if(auth()->user()->name == $devis->nom_client){
                    $devis->etat = 'Refuse';
                    $devis->statut = 'Inactif';
                    $devis->update();
                    return response()->json([
                        'message'=>"Devis refusé avec succès.",
                    ], 200);
                }else{
                    return response()->json([
                        'message'=>"Vous ne pouvez pas faire cet action."
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>"Vous ne pouvez pas faire cet action."
                ]);
            }        
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devis $devis)
    {
        try{
            if(auth()->user()->id == $devis->demenageur_id){
                $devis->delete();
                return response()->json([
                    "message"=>"Devis supprimé avec succès",
                    "Informations du devis"=> [
                        'Nom du client' => $devis->nom_client,
                        'Prix du déménagement'=> $devis->prix_total,
                        'Adresse actuelle du client' => $devis->adresse_actuelle,
                        'Nouvelle adresse actuelle du client' =>$devis->nouvelle_adresse,
                    ]
                ], 200);
            }else{
                return response()->json([
                    "message"=>"Vous n'êtes pas autorisé à faire cet action"
                ], 404);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
}
