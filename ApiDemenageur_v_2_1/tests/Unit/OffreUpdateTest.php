<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\User;
use App\Models\Offre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OffreUpdateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanUpdateOffreSuccessfully()
    {
        $user = User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'telephone' => '+221774532345',
             'role' => 'Demenageur',
            'localite' => 'Dakar',
        ]);
        // dd($user);
        $offre = Offre::create([
            'user_id'=>$user->id,
            'nom_offre'=> 'offre 1',
            'description_offre'=> 'description offre 1',
            'prix_offre'=> 15000,
        ]);
        // dd($offre);
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        $updateoffre = $this->putJson('api/offreupdate/'.$offre->id, [
            'nom_offre'=> 'offre 2',
            'description_offre'=> 'description offre 2',
            'prix_offre'=> 15000,
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $updateoffre->assertStatus(200);
    }

    
}

