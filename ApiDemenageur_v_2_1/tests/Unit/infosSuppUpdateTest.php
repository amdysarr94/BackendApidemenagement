<?php

namespace Tests\Unit;

use App\Models\InformationsSupp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class infosSuppUpdateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanUpdateInfosSuppSuccessfully()
    {
        // $userData = [
        //     'name' => 'Musa',
        //     'email' => 'musa@gmail.com',
        //      'password' => 'password',
        //      'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
        //      'telephone' => '+221774532345',
        //      'role' => 'Demenageur',
        //     'localite' => 'Dakar',
        //  ];
        // $response = $this->postJson('api/register', $userData);
        // $response->assertStatus(201);
        $user= User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
            //  'passwordconfirm' => 'password', // Champ de confirmation du mot de passe
             'telephone' => '+221774532345',
             'role' => 'Demenageur',
            'localite' => 'Dakar',
        ]);

        // Se connecter en tant qu'admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        // dd($token);
        //ajouter un  article
        // $response = $this->actingAs($user)
        // ->postJson('/api/infosupstore', [
        //                     'user_id' =>$user->id,
        //                     'presentation'=> 'présentation 1',
        //                     'NINEA'=> '12345678',
        //                     'nom_entreprise'=> 'test',
        //                     'forme_juridique'=> 'SARL',
        //                     'annee_creation'=> '2010',
        //                 ], [
        //                     'Authorization' => 'Bearer ' . $token,
        //                 ]);
        $infosupp = InformationsSupp::create([
                            'user_id' =>$user->id,
                            'presentation'=> 'présentation 1',
                            'NINEA'=> '12345678',
                            'nom_entreprise'=> 'test',
                            'forme_juridique'=> 'SARL',
                            'annee_creation'=> '2010',
        ]);
        // $response->assertStatus(201);
        $response = $this->actingAs($user)
        ->putJson('/api/infosupupdate/'.$infosupp->id, [
                            'user_id' =>$user->id,
                            'presentation'=> 'présentation 2',
                            'NINEA'=> '12345678',
                            'nom_entreprise'=> 'test',
                            'forme_juridique'=> 'SARL',
                            'annee_creation'=> '2010',
                        ], [
                            'Authorization' => 'Bearer ' . $token,
                        ]);
        $response->assertStatus(200);
    }

}
