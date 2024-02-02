<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class infosUserUpdateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanUpdateInformationsAccountSuccessfully()
    {
         // Création d'un utilisateur pour les besoins du test
         $user = User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'telephone' => '+221774532345',
             'role' => 'Client',
            'localite' => 'Dakar',
        ]);
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password'=> 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion    
        $token = $loginResponse->json()['authorisation']['token'];
        $updateinfos = $this->putJson('api/editprofil/'.$user->id, [
            'name' => 'Musa',
            'email' => 'musa2@gmail.com',
            'password'=>'password',
            'telephone'=>'+221774532345',
            'role'=>'Client',
            'localite'=>'Thiès'
        ]);
        $updateinfos->assertStatus(200);
    }

    
}
