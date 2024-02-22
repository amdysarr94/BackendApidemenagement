<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;
    protected $fillable = [
        
    ];
    public function user(){
        return $this->belongsTo(User::class, 'demenageur_id');
    }
    public function demandedevis(){
        return $this->belongsTo(DemandeDevis::class, 'demande_devis_id');
    }
    public function prestation(){
        return $this->hasOne(Prestation::class);
    }
}
