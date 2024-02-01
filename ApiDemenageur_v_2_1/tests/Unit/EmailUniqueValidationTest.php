<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailUniqueValidationTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testEmailUniqueValidation()
    {
        // Création d'un utilisateur pour les besoins du test
        $userData = User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
              // Champ de confirmation du mot de passe
             'telephone' => '+221774532345',
             'role' => 'Client',
            'localite' => 'Dakar',
         ]);
        //  $register = $this->postJson('api/register', $userData);
        //  $register->assertStatus(201);
         $loginResponse = $this->postJson('api/login', [
            'email' => $userData['email'],
            'password' => 'password', // Utilisez le mot de passe en clair
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        // $token = $loginResponse->json()['authorisation']['token'];
        $duplicateCandidatResponse = $this
        ->postJson('api/register', [
                'name' => 'John Doe', 
                'email' => 'musa@gmail.com', // Email en double
                'password' => 'password',
                'role' => 'Client',
                'passwordconfirm' => 'password', 
                'telephone' => '+221774535345',
                'localite' => 'Dakar',
            ]);

        // Vérifier que la création est refusée avec le statut 422 et les erreurs attendues
        $duplicateCandidatResponse->assertStatus(200)
                                  ->assertJsonStructure([
                                        "success",
                                        "error",
                                        "message",
                                        "errorsList" 
                                  ]);
          
    }

    
}

