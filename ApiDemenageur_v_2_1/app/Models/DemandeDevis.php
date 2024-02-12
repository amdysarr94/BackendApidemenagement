<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDevis extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'nom_client',
        'nom_entreprise',
        'adresse_actuelle',
        'nouvelle_adresse',
        'informations_bagages',
        'date_demenagement',
        'statut'
    ];
    public function user(){
        return $this->belongsTo(User::class, 'client_id');
    }
}
