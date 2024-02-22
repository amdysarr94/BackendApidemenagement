<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationsSupp extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'presentation',
        'NINEA',
        'nom_entreprise',
        'forme_juridique',
        'annee_creation',
    ];
    public function user(){
      return $this->belongsTo(User::class);
    }
}
