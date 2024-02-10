<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        try{
            $role = Role::create([
                'nom_role' => $request->nom_role
            ]);
            return response()->json([
                "message"=>"le role a été créé avec succès",
                "nom du role"=> $role->nom_role
            ], 201);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try{
            $role->nom_role = $request->nom_role;
            $role->update();
            return response()->json([
                "message"=>"Le nom du role modifié avec succès",
                "nom du role"=> $role->nom_role
            ], 200);           
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try{
            $role->delete();
            return response()->json([
                "message"=>"Le role a été supprimé avec succès",
                "nom du role"=> $role->nom_role
            ], 200);          
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        
    }
}
