<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\User;
use App\Models\Offre;
use App\Models\Souscription;
use Illuminate\Http\Request;
use App\Events\SouscriptionValiderEvent;
use App\Http\Requests\SouscriptionStoreRequest;
use App\Http\Requests\SouscriptionUpdateRequest;

class SouscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function souscriptionActifOfOneCustomer(User $customer){
        $souscriptionofMovers = Souscription::where('client_id', $customer->id)->get();
        foreach($souscriptionofMovers as $souscriptionofMover){
            if($souscriptionofMover->statut == 'Actif'){
                return response()->json(compact('souscriptionofMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription actif pour ce client"
                ], 200);
            }
        }
    }
    public function souscriptionInactifOfOneCustomer(User $customer){
        $souscriptionofMovers = Souscription::where('client_id', $customer->id)->get();
        foreach($souscriptionofMovers as $souscriptionofMover){
            if($souscriptionofMover->statut == 'Inactif'){
                return response()->json(compact('souscriptionofMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription inactif pour ce client"
                ], 200);
            }
        }
    }
    public function allSouscriptionOfOneCustomer(User $customer){
        $souscriptionofMovers = Souscription::where('client_id', $customer->id)->get();
        if($souscriptionofMovers){
            return response()->json(compact('souscriptionofMovers'), 200);
        }else{
            return response()->json([
                'message'=> "Il n'y a pas de souscription pour ce client"
            ], 200);
        }
    }
    public function souscriptionActifOfOneMover(Offre $offre){
        $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->get();
        foreach($souscriptionofMovers as $souscriptionofMover){
            if($souscriptionofMover->statut == 'Actif'){
                return response()->json(compact('souscriptionofMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription actif pour cet offre"
                ], 200);
            }
        }
    }
    public function souscriptionInactifOfOneMover(Offre $offre){
        $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->get();
        foreach($souscriptionofMovers as $souscriptionofMover){
            if($souscriptionofMover->statut == 'Inactif'){
                return response()->json(compact('souscriptionofMover'), 200);
            }else{
                return response()->json([
                    'message'=> "Il n'y a pas de souscription inactif pour cet offre"
                ], 200);
            }
        }
    }
    public function allSouscriptionOfOneMover(Offre $offre){
        $souscriptionofMovers = Souscription::where('offre_id', $offre->id)->get();
        if($souscriptionofMovers){
            return response()->json(compact('souscriptionofMovers'), 200);
        }else{
            return response()->json([
                'message'=> "Il n'y a pas de souscription pour cet offre"
            ], 200);
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
    public function store(SouscriptionStoreRequest $request, Offre $offre)
    {
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
        $jour_j = new DateTime($request->date_demenagement);
        $diff = $jour_j->diff($currentDay);
        $limiteMax = $currentDay->modify('+60 days');
        // dd($limiteMax, $jour_j, );
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
        

    }

    /**
     * Display the specified resource.
     */
    public function show(Souscription $souscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Souscription $souscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SouscriptionUpdateRequest $request, Souscription $souscription)
    {

        if(auth()->user()->id == $souscription->client_id && $souscription->etat == 'En cours'){
            $souscription->adresse_actuelle = $request->adresse_actuelle;
            $souscription->nouvelle_adresse = $request->nouvelle_adresse;
            $currentDay = new DateTime();
            $jour_j = new DateTime($request->date_demenagement);
            $diff = $jour_j->diff($currentDay);
            $limiteMax = $currentDay->modify('+60 days');
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
        
    }
    public function valider(Souscription $souscription){
        $user_id= auth()->user()->id;
        $nom_offre = $souscription->nom_offre;
        $souscriptions = Souscription::whereHas('offre', function ($query) use ($user_id, $nom_offre) {
            $query->where('user_id', $user_id)
                  ->where('nom_offre', $nom_offre);
        })->get();
        foreach($souscriptions as $souscript){
            if($souscript == $souscription){
                if(auth()->user()->role == 'Demenageur' && $souscription->etat == 'En cours'){
                    event(new SouscriptionValiderEvent($souscription->id));
                    $souscription->etat == 'Valide';
                    $souscription->update();
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
        }
        
        
    }
    public function refuser(Souscription $souscription){
        $user_id= auth()->user()->id;
        $nom_offre = $souscription->nom_offre;
        $souscriptions = Souscription::whereHas('offre', function ($query) use ($user_id, $nom_offre) {
            $query->where('user_id', $user_id)
                  ->where('nom_offre', $nom_offre);
        })->get();
        foreach($souscriptions as $souscript){
            if($souscript == $souscription && auth()->user()->role  == 'Demenageur'){
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
        }
    }
    public function activate(Souscription $souscription){
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
        
    }
    public function desactivate(Souscription $souscription){
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
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Souscription $souscription)
    {
        $souscription->delete();
        return response()->json([
            "message"=>"Souscription supprimée avec succès",
            "Informations de la souscription"=> [
                "Nom de l'offre" => $souscription->nom_offre,
                "Description de l'offre" => $souscription->description,
                "Nom du client" => $souscription->nom_client
            ]
        ], 200);
    }
}
