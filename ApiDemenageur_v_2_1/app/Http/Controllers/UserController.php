<?php

namespace App\Http\Controllers;

use App\Http\Requests\resetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function allActifUser(){
        $usersActifs = User::where('etat', 'Actif')->paginate(10);
        return UserResource::collection($usersActifs);
    }
    public function allInactifUser(){
        $usersInactifs = User::where('etat', 'Inactif')->paginate(10);
        return UserResource::collection($usersInactifs);
    }
    public function allCustomerUser(){
        $usersCustomer = User::where('role', 'Client')->paginate(10);
        return UserResource::collection($usersCustomer);
    }
    public function allMoverUser(){
        $usersCustomer = User::where('role', 'Demenageur')->paginate(10);
        return UserResource::collection($usersCustomer);
    }
    public function show(User $user){
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
        
    }
    public function update(UserUpdateRequest $request, User $user){
        if(auth()->user()->id == $user->id){
            $user->name = $request->name;
            $user->email = $request->email;
            // $user->photo_profile = $request->photo_profile;
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
        
    }
    public function resetPassword(resetPasswordRequest $request){
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
    }
    public function activate(User $user){
        if(auth()->user()->id == $user->id || auth()->user()->role = 'Admin'){
            if($user->etat == 'Actif'){
                $user->etat = 'Inactif';
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
    }
    public function desactivate(User $user){
        if(auth()->user()->id == $user->id || auth()->user()->role = 'Admin'){
            if($user->etat == 'Inactif'){
                $user->etat = 'Actif';
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
    }
}
