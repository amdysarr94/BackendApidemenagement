<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function demandedevis(){
        return $this->belongsTo(DemandeDevis::class);
    }
    public function prestation(){
        return $this->hasOne(Prestation::class);
    }
}
