<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegistredRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','refresh','logout']]);
    }
    public function register(UserRegistredRequest $request){
        try{
            if($request->password == $request->passwordconfirm){
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                if($request->file('photo_profile')){
                    $file = $request->file('photo_profile'); 
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('images'), $filename);
                    $user['photo_profile'] = $filename;
                }
                
                $user->password = $request->password;
                $user->telephone = $request-> telephone;
                $user->role = $request->role;
                $user->localite = $request->localite;
                // dd($user);
                $user->save();
               
                return response()->json([
                    'status_message'=> "Utilisateur enregistré",
                    'user'=>[
                        'Nom' => $user->name,
                        'Email' => $user->email,
                        'role'=>$user->role
                    ]
                ], 201);
            }
           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès refusé',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        if($user->etat == 'Inactif'){
            return response()->json([
                'status' => 'error',
                'message' => "Ce compte n'existe plus !!!",
            ], 401);
        }
        return response()->json([
                'status' => 'success',
                'user' => [
                    'id'=>$user->id,
                    'Nom'=> $user->name,
                    'Email' =>  $user->email,
                    'Localite'=>$user->localite,
                    'Telephone'=>$user->telephone
                ],
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);

    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie',
        ], 200);
    }
}
