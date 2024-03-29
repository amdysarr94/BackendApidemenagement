<?php

namespace App\Models;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'passwordconfirm',
        'telephone',
        'role',
        'localite'
    ];
    public function demandedevis(){
      return $this->hasMany(DemandeDevis::class);
    }
    public function devis(){
      return $this->hasMany(Devis::class);
    }
    public function prestationascustomer(){
      return $this->hasOne(Prestation::class, 'client_id');
    }
    public function prestationasmover(){
      return $this->hasOne(Prestation::class, 'demenageur_id');
    }
    public function offre(){
      return $this->hasMany(Offre::class);
    }
    public function informationssupp(){
      return $this->hasOne(InformationsSupp::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    //put these methods at the bottom of your class body
  
   public function getJWTIdentifier()
   {
     return $this->getKey();
   }

   public function getJWTCustomClaims()
   {
     return [
       'email'=>$this->email,
       'name'=>$this->name
     ];
   }
}
