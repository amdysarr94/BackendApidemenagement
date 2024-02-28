<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;
    
    public function user(){
        return $this->belongsTo(User::class, 'client_id');
    }
      public function mover(){
        return $this->belongsTo(User::class, 'demenageur_id');
    }
    
}
