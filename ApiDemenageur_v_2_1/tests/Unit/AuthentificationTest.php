<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;


class AuthentificationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    /**
     * A basic unit test example.
     */
    public function testUserCanRegisterSuccessfully()
    {
        $userData = [
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password', // Champ de confirmation du mot de passe
            'telephone' => '+221774532345',
            'role' => 'user',
            'localite' => 'Paris',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status_message',
                'user' => [
                    'Nom',
                    'Email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
            'telephone' => '0123456789',
            'role' => 'user',
            'localite' => 'Paris',
        ]);
    }
    public function testUserCanLoginSuccessfully()
    {
        // Création d'un utilisateur pour les besoins du test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Utilisation de Hash::make() pour le hachage du mot de passe
            'etat' => 'Actif', // Assurez-vous que l'état de l'utilisateur est actif pour ce test
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('api/login', $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'Nom',
                    'Email',
                ],
                'authorisation' => [
                    'token',
                    'type',
                ],
            ]);

        // Vérifier que l'utilisateur est connecté
        $this->assertAuthenticated();
    }

    public function testUserCannotLoginWithInactiveAccount()
    {
        // Création d'un utilisateur inactif pour les besoins du test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Utilisation de Hash::make() pour le hachage du mot de passe
            'etat' => 'Inactif', // Assurez-vous que l'état de l'utilisateur est inactif pour ce test
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('api/login', $credentials);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => "Ce compte n'existe plus !!!",
            ]);

        // Vérifier que l'utilisateur n'est pas connecté
        $this->assertGuest();
    }

    public function testUserCanLogoutSuccessfully()
    {
        // Authentification d'un utilisateur pour le test de déconnexion
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Déconnexion réussie',
            ]);

        // Vérifier que l'utilisateur est déconnecté
        $this->assertGuest();
    }
}
