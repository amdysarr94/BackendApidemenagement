<?php

namespace App\Http\Controllers;

use App\Models\DemandeDevis;
use Illuminate\Http\Request;
use App\Http\Requests\DemandeDevisStoreRequest;
use App\Http\Requests\DemandeDevisUpdateRequest;

class DemandeDevisController extends Controller
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
    public function store(DemandeDevisStoreRequest $request)
    {
        $demandedevis = new DemandeDevis();
        $demandedevis->user_id = auth()->user()->id;
        
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeDevis $demandeDevis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeDevis $demandeDevis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandeDevisUpdateRequest $request, DemandeDevis $demandeDevis)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeDevis $demandeDevis)
    {
        //
    }
}
