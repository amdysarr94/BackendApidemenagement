<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Models\Prestation;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function prestationActifOfOneCustomer(User $customer){
        try{
            $prestationOfMovers= Prestation::where('nom_client', $customer->name)->where('statut', 'Actif')->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation actif pour ce client"
                ], 200);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function prestationInactifOfOneCustomer(User $customer){
        try{
            $prestationOfMovers= Prestation::where('nom_client', $customer->name)->where('statut', 'Inactif')->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation inactif pour ce client"
                ], 200);
            }         
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function allPrestationOfOneCustomer(User $customer){
        try{
            $prestationOfMovers= Prestation::where('nom_client', $customer->name)->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation  concernant ce client"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function prestationActifOfOneMover(User $demenageur){
        try{
            $prestationOfMovers= Prestation::where('nom_entreprise', $demenageur->name)->where('statut', 'Actif')->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation actif pour ce demenageur"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function prestationInactifOfOneMover(User $demenageur){
        try{
            $prestationOfMovers= Prestation::where('nom_entreprise', $demenageur->name)->where('statut', 'Inactif')->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation inactif pour ce demenageur"
                ], 200);
            }     
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function allPrestationOfOneMover(User $demenageur){
        try{
            $prestationOfMovers= Prestation::where('nom_entreprise', $demenageur->name)->get();
            if($prestationOfMovers){
                return response()->json(compact('prestationOfMovers'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de prestation  pour ce déménageur"
                ], 200);
            }           
        }catch(Exception $e){
            return response()->json($e);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestation $prestation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestation $prestation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestation $prestation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestation $prestation)
    {
        //
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
            return response()->json($e);
        }

        
    }
}
