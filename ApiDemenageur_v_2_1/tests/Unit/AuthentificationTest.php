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
            'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
            'telephone' => '+221774532345',
            'role' => 'Client',
            'localite' => 'Dakar',
        ];

        $response = $this->postJson('api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status_message',
                'user' => [
                    'Nom' ,
                    'Email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
            'telephone' => '+221774532345',
            'role' => 'Client',
            'localite' => 'Dakar',
        ]);
    }
    public function testUserCanLoginSuccessfully()
    {
        $userData = [
                    'name' => 'Musa',
                    'email' => 'musa@gmail.com',
                     'password' => 'password',
                     'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
                     'telephone' => '+221774532345',
                     'role' => 'Client',
                    'localite' => 'Dakar',
                 ];
        $response = $this->postJson('api/register', $userData);
        $response->assertStatus(201);
        $loginResponse = $this->postJson('api/login', [
            'email' => $userData['email'],
            'password' => $userData['password'], 
        ]);

        $loginResponse->assertStatus(200)
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
    }

}
