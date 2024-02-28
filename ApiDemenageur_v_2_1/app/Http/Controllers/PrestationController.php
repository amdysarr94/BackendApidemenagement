<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Models\Prestation;
use Illuminate\Http\Request;
use App\Http\Requests\PrestationCommentRequest;
use App\Models\InformationsSupp;

class PrestationController extends Controller
{
    public function prestationActifOfOneCustomer(User $customer){
        try{
            $prestationOfCustomer= Prestation::where('nom_client', $customer->name)->where('statut', 'Actif')->with(['user','mover'])->get();
            if($prestationOfCustomer){
                return response()->json([
                    'status'=>"succes",
                    'message'=> "La liste de toutes les prestations actives concernant un client",
                    'data'=>$prestationOfCustomer
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestations actives pour ce client"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function prestationInactifOfOneCustomer(User $customer){
        try{
            $prestationOfCustomer= Prestation::where('nom_client', $customer->name)->where('statut', 'Inactif')->with(['user','mover'])->get();
            if($prestationOfCustomer){
                return response()->json([
                    'status'=>"succes",
                    'message'=> "La liste de toutes les prestations inactives concernant un client",
                    'data'=>$prestationOfCustomer
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestations inactives pour ce client"
                ], 200);
            }         
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allPrestationOfOneCustomer(User $customer){
        try{
            $prestationOfCustomer= Prestation::where('nom_client', $customer->name)->with(['user','mover'])->get();
            if($prestationOfCustomer){
                return response()->json([
                    'status'=>"succes",
                    'message'=> "La liste de toutes les prestations concernant un client",
                    'data'=>$prestationOfCustomer
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestations concernant ce client"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function prestationActifOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->with('user')->get()->first();
            //  dd($infoSupp);
            $prestationOfMovers= Prestation::where('nom_entreprise',$infoSupp->nom_entreprise)->where('statut', 'Actif')->with('user')->get();
            if($prestationOfMovers){
                // dd($prestationOfMovers);
                return response()->json([
                    'status'=>"succès",
                    'message'=>"La liste des prestations actives d'un déménageur",
                    'data'=>$prestationOfMovers,
                    'Informations du déménageur'=> $infoSupp
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestations actives pour ce demenageur"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function prestationInactifOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->with('user')->get()->first();
            $prestationOfMovers= Prestation::where('nom_entreprise', $infoSupp->nom_entreprise)->where('statut', 'Inactif')->get();
            if($prestationOfMovers){
                return response()->json([
                    'status'=>"succès",
                    'message'=>"La liste des prestations inactives d'un déménageur",
                    'data'=>$prestationOfMovers,
                    'informations supplémentaires'=>$infoSupp
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation inactives pour ce demenageur"
                ], 200);
            }     
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allPrestationOfOneMover(User $demenageur){
        try{
            $infoSupp = InformationsSupp::where('user_id', $demenageur->id)->with('user')->get()->first();
            $prestationOfMovers= Prestation::where('nom_entreprise', $infoSupp->nom_entreprise)->get();
            if($prestationOfMovers){
                return response()->json([
                    'status'=>"succès",
                    'message'=>"La liste de toutes les prestations  d'un déménageur",
                    'data'=>$prestationOfMovers,
                    'informations supplémentaires'=>$infoSupp
                ], 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation  pour ce déménageur"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestation $prestation)
    {
        //
    }
    public function send(PrestationCommentRequest $request, Prestation $prestation){
        // $now = new DateTime();
        if(auth()->user()->id == $prestation->client_id && $prestation->etat == 'Termine'){
            $prestation->commentaire = $request->commentaire;
            $prestation->update();
            return response()->json([
                'message'=> "Votre commentaire a été ajouté avec succès !",
                "Informations"=> $prestation
            ], 200);
        }
    }
    public function finish(Prestation $prestation){
        // nom_entreprise
        $infosSuppOfMover = InformationsSupp::where('nom_entreprise', $prestation->nom_entreprise)
                                            ->where('user_id', auth()->user()->id)
                                            ->get()->first();
        if($infosSuppOfMover){
            if(auth()->user()->id == $infosSuppOfMover->user_id){
                // dd('Terminé');
                $prestation->etat = 'Termine';
                // $prestation->statut = 'Inactif';
                $prestation->update();
                return response()->json([
                    'message'=>"Prestation marquée comme terminer !",
                    "Informations"=>$prestation
                ], 200);

            }else{
                // dd("Tu ne peux pas faire ça !");
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à effectuer cette action !"
                ], 404);
            }
        }else{
                // dd('erreur');
                return response()->json([
                    'message'=>""
                ]);
        }
    }
    public function cancel(Prestation $prestation){
        try{
            if(auth()->user()->name == $prestation->nom_client){
                $currentDate = new DateTime();
                if($currentDate <= $prestation->delai){
                    $prestation->etat == 'Annule';
                    $prestation->statut == 'Inactif';
                    $prestation->update();
                    return response()->json([
                        'message'=> "Votre déménagement a été annulé",
                        "Informations"=> $prestation
                    ], 200);
                }else{
                    return response()->json(['message'=>"Vous ne pouvez plus annuler votre prestation. 
                    Pour en savoir plus, référé vous à notre politique générale"]);
                }
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
}
