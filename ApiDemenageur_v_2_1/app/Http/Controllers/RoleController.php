<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(RoleStoreRequest $request)
    {
        $role = Role::create([
            'nom_role' => $request->nom_role
        ]);
        return response()->json([
            "message"=>"le role a été créé avec succès",
            "nom du role"=> $role->nom_role
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->nom_role = $request->nom_role;
        $role->update();
        return response()->json([
            "message"=>"Le nom du role modifié avec succès",
            "nom du role"=> $role->nom_role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            "message"=>"Le role a été supprimé avec succès",
            "nom du role"=> $role->nom_role
        ], 200);
    }
}
