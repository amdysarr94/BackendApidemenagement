<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class ArticlesCreatedTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testcreateArticleSuccessfully(){
        $userData = [
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
             'telephone' => '+221774532345',
             'role' => 'Admin',
            'localite' => 'Dakar',
         ];
        $response = $this->postJson('api/register', $userData);
        $response->assertStatus(201);
        // Se connecter en tant qu'admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $userData['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        //ajouter un  article
        $response = $this->postJson('/api/storearticle', [
                            'titre'=> 'titre 1',
                            'contenu'=> 'contenue 1'
                        ], [
                            'Authorization' => 'Bearer ' . $token,
                        ]);
        $response->assertStatus(201);
    }  
}
