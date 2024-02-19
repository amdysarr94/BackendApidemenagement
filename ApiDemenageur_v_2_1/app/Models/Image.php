<?php

namespace App\Models;

use App\Models\DemandeDevis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    public function demandedevis(){
        return $this->belongsTo(DemandeDevis::class);
    }
}
