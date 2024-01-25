<?php

namespace App\Http\Controllers;

use App\Http\Requests\resetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function update(UserUpdateRequest $request, User $user){
        $user->name = $request->name;
        $user->email = $request->email;
        $user->photo_profile = $request->photo_profile;
        $user->password = $request->password;
        $user->telephone = $request->telephone;
        $user->role = $request->role;
        $user->localite = $request->localite;
        $user->update();
        return response()->json([
            'status_message'=> "Information de compte modifiées avec succès!",
            'user'=>[
                'Nom' => $user->name,
                'Email' => $user->email
            ]
        ], 200);
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
}
