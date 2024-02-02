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
        'date_demenagement'
    ];
    public function user(){
        return $this->belongsToMany(User::class);
    }
}
