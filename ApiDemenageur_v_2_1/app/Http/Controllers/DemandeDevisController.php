<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Mail\DevisSendMail;
// use Illuminate\Support\Carbon;
use App\Models\DemandeDevis;
use Illuminate\Http\Request;
use App\Models\InformationsSupp;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\DemandeDevisStoreRequest;
use App\Http\Requests\DemandeDevisUpdateRequest;
use App\Notifications\DemandeDevisSendNotification;

class DemandeDevisController extends Controller
{
    
    public function demandeDevisActifOfOneCustomer(User $customer){
        try{
            $demandeDevisOfCustomers = DemandeDevis::where('nom_client', $customer->name)->where('statut', 'Actif')->get();
            if($demandeDevisOfCustomers){
                return response()->json([
                    'status' => 'success',
                    'status_message' => 'tous les demandes de devis ont été recupérées',
                    'data' => $demandeDevisOfCustomers,
                    'informations du client' => [
                        "Nom complet"=>$customer->name,
                        "Email"=>$customer->email,
                        "Telephone"=>$customer->telephone
                    ]
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas reçu de demande de devis"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function demandeDevisInactifOfOneCustomer(User $customer){
        try{
            $demandeDevisOfCustomers = DemandeDevis::where('nom_client', $customer->name)->where('statut', 'Inactif')->get();
            if($demandeDevisOfCustomers){
                return response()->json(compact('demandeDevisOfCustomers'), 200);
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas de demande de devis"
                ], 200);
            }
               
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function AlldemandeDevisOfOneCustomer(User $customer){
        try{
            $demandeDevisOfCustomers = DemandeDevis::where('nom_client', $customer->name)->get();
            if($demandeDevisOfCustomers){
                return response()->json(compact('demandeDevisOfCustomers'), 200);
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas reçu de demande de devis"
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allDemandeDevisOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->get()->first();
            $demandeDevisOfMovers = DemandeDevis::where('nom_entreprise', $infoSupp->nom_entreprise)->with('user')->get();
            if($demandeDevisOfMovers){
                return response()->json([
                    'status' => "succes",
                    'message' => "La liste des demandes de devis d'un déménageur",
                    'data 1'=>$demandeDevisOfMovers,
                ], 200);
               
                
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas reçu de demande de devis"
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function demandeDevisActifOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->get()->first();
            $demandeDevisOfMovers = DemandeDevis::where('nom_entreprise', $infoSupp->nom_entreprise)->where('statut', 'Actif')->get();
            if($demandeDevisOfMovers){
                return response()->json(compact('demandeDevisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas reclamation de demande de devis"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function demandeDevisInactifOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->get()->first();
            $demandeDevisOfMovers = DemandeDevis::where('nom_entreprise', $infoSupp->nom_entreprise)->where('statut', 'Inactif')->get();
            if($demandeDevisOfMovers){
                return response()->json(compact('demandeDevisOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Vous n'avez pas reclamation de demande de devis"
                ], 200);
            }        
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandeDevisStoreRequest $request, User $demenageur)
    {
        try{
            if(auth()->user()->role == 'Client'){
                $demandedevis = new DemandeDevis();
                $demandedevis->client_id = auth()->user()->id;
                $demandedevis->nom_client = auth()->user()->name;
                $nom_entreprise = InformationsSupp::where('user_id', $demenageur->id)->get()->first()->nom_entreprise;
                $demandedevis->nom_entreprise = $nom_entreprise;
                $demandedevis->adresse_actuelle = $request->adresse_actuelle;
                $demandedevis->nouvelle_adresse = $request->nouvelle_adresse;
                $demandedevis->informations_bagages = $request->informations_bagages;
                $currentDay = new DateTime();
                // dd($currentDay);
                $jour_j = new DateTime($request->date_demenagement);
                $diff = $jour_j->diff($currentDay);
                
                $currentDayTwo = new DateTime();
                $limiteMax = $currentDayTwo->modify('+60 days');
                // dd($jour_j);
                // dd($diff->days >= 10 && $jour_j > $currentDay && $jour_j < $limiteMax);
                if($diff->days >= 10 && $jour_j > $currentDay && $jour_j < $limiteMax){
                    $demandedevis->date_demenagement = $request->date_demenagement;
                    $demandedevis->save();
                    $infoSupp = InformationsSupp::where('nom_entreprise', $demandedevis->nom_entreprise)->get()->first();
                    $demenageur = User::where('id', $infoSupp->user_id)->get()->first();
                    $demenageur->notify(new DemandeDevisSendNotification($demandedevis));
                    return response()->json([
                        'status' => 'success',
                        'Message' => 'Demande de devis envoyé avec succès',
                        'Infos demande de devis' => [
                            $demandedevis->nom_client,
                            $demandedevis->adresse_actuelle,
                            $demandedevis->nouvelle_adresse
                            ]
                    ], 201);
                    
                    
                }else{
                    return response()->json([
                        'message' => "Votre date de déménagement doit être à plus de 10 jours 
                        et 60 jours de la date d'aujourd'hui"
                    ]);
                }
            }else{
                  return response()->json([
                     'message'=> "Vous ne pouvez pas effectué cette action."
                  ], 404);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
        
        
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandeDevisUpdateRequest $request, DemandeDevis $demandeDevis)
    {
        try{
            if(auth()->user()->id == $demandeDevis->client_id){
                $demandeDevis->nom_entreprise = $request->nom_entreprise;
                $demandeDevis->adresse_actuelle = $request->adresse_actuelle;
                $demandeDevis->nouvelle_adresse = $request->nouvelle_adresse;
                $demandeDevis->informations_bagages = $request->informations_bagages;
                $currentDay = new DateTime();
                $jour_j = new DateTime($request->date_demenagement);
                $currentDayTwo = new DateTime();
                $diff = $jour_j->diff($currentDayTwo);
                $limiteMax = $currentDay->modify('+60 days');
                // dd($limiteMax, $jour_j, );
                if($diff->days >= 10 && $jour_j > $currentDay && $jour_j <= $limiteMax){
                    $demandeDevis->date_demenagement = $request->date_demenagement;
                    $demandeDevis->update();
                    return response()->json([
                        'status' => 'success',
                        'Message' => 'Demande de devis modifié avec succès',
                        'Infos demande de devis' => [
                            $demandeDevis->nom_client,
                            $demandeDevis->adresse_actuelle,
                            $demandeDevis->nouvelle_adresse
                            ]
                    ], 200);
                }else{
                    return response()->json([
                        'message' => "Votre date de déménagement doit être à plus de 10 jours et 60 jours de la date d'aujourd'hui"
                    ]);
                }
                
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function desactivate(DemandeDevis $demandeDevis){
        try{
            if(auth()->user()->id == $demandeDevis->client_id){
                $demandeDevis->statut = 'Inactif';
                $demandeDevis->update();
                return response()->json([
                    'status' => 'success',
                    'Message' => 'Demande de devis supprimé avec succès',
                    'Infos demande de devis' => [
                        $demandeDevis->nom_client,
                        $demandeDevis->adresse_actuelle,
                        $demandeDevis->nouvelle_adresse
                        ]
                ], 200);
            }         
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeDevis $demandeDevis)
    {
        try{
            if(auth()->user()->role == 'Admin'){
                $demandeDevis->delete();
                return response()->json([
                    'status' => 'success',
                    'Message' => 'Demande de devis Supprimé avec succès',
                    'Infos demande de devis' => [
                        $demandeDevis->nom_client,
                        $demandeDevis->adresse_actuelle,
                        $demandeDevis->nouvelle_adresse
                        ]
                ], 200);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
}
