<?php

namespace App\Http\Controllers;

use App\Models\Souscription;
use Illuminate\Http\Request;
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
    public function store(SouscriptionStoreRequest $request)
    {
        
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Souscription $souscription)
    {
        //
    }
}
