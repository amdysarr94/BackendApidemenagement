<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class ListOfDemandeDevisOfOneMoversTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testListDemandeDevisOfOneMoversSuccessfully()
    {
        // Création d'un utilisateur Demenageur pour les besoins du test
        $userData = [
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
             'telephone' => '+221774532345',
             'role' => 'Client',
            'localite' => 'Dakar',
         ];
        
         $responseOne = $this->postJson('api/register', $userData);
         $responseOne->assertStatus(201);
        //  $responseTwo = $this->postJson('api/register', $userDataDeux);
        //  $responseTwo->assertStatus(201);
         $loginResponseOne = $this->postJson('api/login', [
            'email' => $userData['email'],
            'password' => 'password',
        ]);
        $loginResponseOne->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponseOne->json()['authorisation']['token'];
        $responseOne = $this->postJson('/api/demandedevisstore', [
            'nom_entreprise'=> 'titre 2',
            'adresse_actuelle'=> 'contenue 2',
            'nouvelle_adresse'=> 'contenue 2',
            'informations_bagages'=> "J'ai peu de bagages",
            'date_demenagement'=>'2024-02-14'
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $responseOne->assertStatus(201);
        
        $logoutResponse = $this->postJson('/api/logout', [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $logoutResponse->assertStatus(200);
        $userDataDeux = [
            'name' => 'Adama',
            'email' => 'adama@gmail.com',
             'password' => 'password',
             'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
             'telephone' => '+221774532355',
             'role' => 'Demenageur',
            'localite' => 'Dakar',
         ];
         $responseOne = $this->postJson('api/register', $userDataDeux);
         $responseOne->assertStatus(201);
         $loginResponseOne = $this->postJson('api/login', [
            'email' => $userDataDeux['email'],
            'password' => 'password',
        ]);
        $loginResponseOne->assertStatus(200);
        // Récupérer le token après la connexion
        $tokenDeux = $loginResponseOne->json()['authorisation']['token'];
    }

    
}

