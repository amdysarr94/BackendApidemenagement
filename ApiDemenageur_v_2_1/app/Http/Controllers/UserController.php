<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\SearchMoverRequest;
use App\Http\Requests\resetPasswordRequest;

class UserController extends Controller
{
    public function allActifUser(){
        try{
            $usersActifs = User::where('etat', 'Actif')->paginate(10);
            return UserResource::collection($usersActifs);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }
    public function listofmoversofonelocality(SearchMoverRequest $request){
        try{
            $localite = $request->localite;
            $MoversOfLocality = User::where('role', 'Demenageur')->where('etat', 'Actif')->where('localite', $localite)->with('informationssupp')->get();
            if($MoversOfLocality){
                return response()->json([
                    'message'=> "La liste des déménageurs de la localité",
                    'informations'=>$MoversOfLocality
                ], 200);
            }else{
                return response()->json([
                    'message'=>"Il n'y a pas de déménageurs dans cette localité"
                ]);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function allInactifUser(){
        try{
            $usersInactifs = User::where('etat', 'Inactif')->paginate(10);
            return UserResource::collection($usersInactifs);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allCustomerUser(){
        try{
            $usersCustomer = User::where('role', 'Client')->paginate(10);
            return UserResource::collection($usersCustomer);            
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function allMoverUser(){
        try{
            $usersCustomer = User::where('role', 'Demenageur')->paginate(10);
            return UserResource::collection($usersCustomer);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function show(User $user){
        try{
            if(auth()->user()->id == $user->id || auth()->user()->role == 'Admin'){
                return response()->json([
                    'status'=> 'success',
                    'message'=> "Les informations de l'utilisateur",
                    'Informations' => [
                        'Nom' => $user->name,
                        'Email' => $user->email,
                        'telephone'=> $user->telephone,
                        'role'=> $user->role
                    ]
                ]);
            }else{
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à consulter ce profil"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function update(UserUpdateRequest $request, User $user){
        try{
            if(auth()->user()->id == $user->id){
                $user->name = $request->name;
                $user->email = $request->email;
                // $user->photo_profile = $request->photo_profile;
                if($request->file('photo_profile')){
                    $file = $request->file('photo_profile'); 
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('images'), $filename);
                    $user['photo_profile'] = $filename;
                }
                $user->password = $request->password;
                $user->telephone = $request->telephone;
                switch($user->role){
                    case 'Admin':
                        $user->role = 'Admin';
                        break;
                    case 'Client':
                        $user->role = 'Client';
                        break;
                    case 'Demenageur':
                        $user->role = 'Demenageur';
                        break;
                }
                // $user->role = $request->role;
                $user->localite = $request->localite;
                $user->update();
                return response()->json([
                    'status_message'=> "Information de compte modifiées avec succès!",
                    'user'=>[
                        'Nom' => $user->name,
                        'Email' => $user->email
                    ]
                ], 200);
            }else{
                return response()->json([
                    'message'=>"Vous n'êtes pas autorisé à effectuer cette action"
                ]);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
        
    }
    public function resetPassword(resetPasswordRequest $request){
        try{
            if($request->telephone && $request->email){
                $telephone = $request->telephone;
                $email = $request->email;
                if(User::where('telephone', $telephone)->get()->first() == User::where('email', $email)->get()->first()){
                    $user = User::where('email', $email)->get()->first();
                    $user->password = $request->password;
                    $user->update();
                    return response()->json([
                        'status_message'=> "Mot de passe réinitialisé avec succès!",
                        'user'=>[
                            'Nom' => $user->name,
                            'Email' => $user->email
                        ]
                    ], 200);
                }else {
                    return response()->json([
                        'status_code'=>403,
                       'status_message'=> "Identifiants invalides"
                    ]);
                }
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function activate(User $user){
        try{
            if(auth()->user()->id == $user->id || auth()->user()->role = 'Admin'){
                if($user->etat == 'Inactif'){
                    $user->etat = 'Actif';
                    $user->update();
                    return response()->json([
                        'messsage'=>"Ce compte est activé avec succès !",
                        'data'=>$user
                    ]);
                }else{
                    return response()->json([
                        'messsage'=>"Ce compte est déjà actif !"
                    ]);
                }
            }else{
                return response()->json([
                    'message'=> "Vous n'êtes pas autorisé à effectué cet action"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
    public function desactivate(User $user){
        try{
            if(auth()->user()->id == $user->id || auth()->user()->role = 'Admin'){
                if($user->etat == 'Actif'){
                    $user->etat = 'Inactif';
                    $user->update();
                    return response()->json([
                        'messsage'=>"Ce compte est désactivé avec succès !",
                        'data'=>$user
                    ]);
                }else{
                    return response()->json([
                        'messsage'=>"Ce compte est déjà inactif !"
                    ]);
                }
            }else{
                return response()->json([
                    'message'=> "Vous n'êtes pas autorisé à effectué cet action"
                ], 403);
            }          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
}
