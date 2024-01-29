<?php

namespace App\Http\Controllers;

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
        $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->get();
        foreach($devisOfMovers as $devisOfMover){
            if($devisOfMover->statut == 'Actif'){
                return response()->json(compact('devisOfMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce client n'a pas de devis actifs"
                ]);
            }
        }
    }
    public function devisInactifOfOneCustomer(User $customer){
        $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->get();
        foreach($devisOfMovers as $devisOfMover){
            if($devisOfMover->statut == 'Inactif'){
                return response()->json(compact('devisOfMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce client n'a pas de devis inactifs"
                ]);
            }
        }
    }
    public function allDevisOfOneCustomer(User $customer){
        $devisOfMovers = Devis::where('nom_client', $customer->nom_client)->get();
        if($devisOfMovers){
            return response()->json(compact('devisOfMovers'), 200);
        }else{
            return response()->json([
                'message'=> "Ce client n'a pas reçu de devis."
            ]);
        }
    }
    public function devisActifOfOneMover(User $demenageur){
        $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->get();
        foreach($devisOfMovers as $devisOfMover){
            if($devisOfMover->statut == 'Actif'){
                return response()->json(compact('devisOfMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce déménageur n'a pas de devis actifs"
                ]);
            }
        }
    }
    public function devisInactifOfOneMover(User $demenageur){
        $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->get();
        foreach($devisOfMovers as $devisOfMover){
            if($devisOfMover->statut == 'Inactif'){
                return response()->json(compact('devisOfMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Ce déménageur n'a pas de devis inactifs"
                ]);
            }
        }
    }
    public function AllDevisOfOneMover(User $demenageur){
        $devisOfMovers = Devis::where('demenageur_id', $demenageur->id)->get();
        if($devisOfMovers){
            return response()->json(compact('devisOfMovers'), 200);
        }else{
            return response()->json([
                'message'=> "Ce déménageur n'a pas de devis."
            ]);
        }
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
    public function store(DevisStoreRequest $request, DemandeDevis $demandeDevis)
    {
        
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
            return response()->json([
                "message"=>"Devis envoyé avec succès",
                "Informations du devis"=> [
                    'Nom du client' => $devis->nom_client,
                    'Prix du déménagement'=> $devis->prix_total,
                    'Description' => $devis->description,
                ]
            ], 201);
            $client = User::where('name', $devis->nom_client)->get()->first();
            $client->notify(new DevisSendNotification($devis));
        }else{
            return response()->json([
                'Message' => "Cette demande a été supprimée"
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Devis $devis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devis $devis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevisUpdateRequest $request, Devis $devis)
    {
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
        
    }
    public function activate(Devis $devis){
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
            
        
    }
    public function desactivate(Devis $devis){
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
    }
    public function valider(Devis $devis){
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
        
        
    }
    public function refuser(Devis $devis){
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
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devis $devis)
    {
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

        }
        
    }
}
