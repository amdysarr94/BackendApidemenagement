<?php

use App\Models\DemandeDevis;
use Tests\TestCase;
use App\Models\User;
use App\Models\InformationsSupp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DevisCreateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanSendDevisSuccessfully()
    {
        $user= User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
            'password' => 'password',
            'telephone' => '+221774532345',
            'role' => 'Demenageur',
            'localite' => 'Dakar',
        ]);

        
        $infosupp = InformationsSupp::create([
            'user_id' =>$user->id,
            'presentation'=> 'présentation 1',
            'NINEA'=> '12345678',
            'nom_entreprise'=> 'test',
            'forme_juridique'=> 'SARL',
            'annee_creation'=> '2010',
        ]);
        
        $user_deux= User::create([
            'name' => 'Musa2',
            'email' => 'musa2@gmail.com',
            'password' => 'password',
            'telephone' => '+221774532345',
            'role' => 'Client',
            'localite' => 'Dakar',
        ]);
        $loginResponse_deux = $this->postJson('api/login', [
            'email' => $user_deux['email'],
            'password' => 'password',
        ]);
        $loginResponse_deux->assertStatus(200);
        // Récupérer le token après la connexion
        $token_deux = $loginResponse_deux->json()['authorisation']['token'];
        $demandeDevis = DemandeDevis::create([
            'client_id' => $user_deux->id,
            'nom_client'=> $user_deux->name,
            'nom_entreprise'=>$infosupp->nom_entreprise,
            'adresse_actuelle'=>"Yoff",
            'nouvelle_adresse'=>"Cambérène",
            'informations_bagages'=>"Test",
            'date_demenagement'=>'2024-02-24',
            'statut'=> 'Actif'
        ]);
        // dd($demandeDevis);
        // Se connecter en tant qu'admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        $response = $this->actingAs($user)
            ->postJson('api/devisstore/'.$demandeDevis->id, [
                
                'prix_total'=> 10000,
                'description'=>"test de devis",

        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(201);
       
    }

    
}

