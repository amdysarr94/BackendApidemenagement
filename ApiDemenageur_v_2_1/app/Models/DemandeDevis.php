<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function images()
    {
        return $this->hasMany(Image::class);
    }

}
