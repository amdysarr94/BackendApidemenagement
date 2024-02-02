<?php

namespace Tests\Unit;

use App\Models\DemandeDevis;
use Tests\TestCase;
use App\Models\User;
use App\Models\InformationsSupp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemandeDevisUpdateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanUpdateDemandeDevisSuccessfully()
    {
         //Créer un déménageur 
         $demenageur = User::create([
            'name' => 'Musa',
            'email' => 'musa2@gmail.com',
             'password' => 'password',
             'telephone' => '+221774532355',
             'role' => 'Demenageur',
            'localite' => 'Dakar',
        ]);
        $loginDemenageur = $this->postJson('api/login', [
            'email' => $demenageur['email'],
            'password' => 'password',
        ]);
        $loginDemenageur->assertStatus(200);
        // Créer une demande de devis
        $infosupp = InformationsSupp::create([
            'user_id' =>$demenageur->id,
            'presentation'=> 'présentation 1',
            'NINEA'=> '12345678',
            'nom_entreprise'=> 'test',
            'forme_juridique'=> 'SARL',
            'annee_creation'=> '2010',
        ]);
        // dd($infosupp);
        $client = User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'telephone' => '+221774532345',
             'role' => 'Client',
            'localite' => 'Dakar',
        ]);
        $loginResponse = $this->postJson('api/login', [
            'email' => $client['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        // dd($token);
        $infos = DemandeDevis::create([
            'client_id'=>$client->id,
            'nom_client'=>$client->name,
            'nom_entreprise'=>$infosupp->nom_entreprise,
            'adresse_actuelle'=>'test',
            'nouvelle_adresse'=>'test',
            'informations_bagages'=> 'test',
            'date_demenagement' =>'2024-02-14'

        ]);
        $updateRequest = $this->actingAs($client)
        ->putJson('api/demandedevisupdate/'.$infos->id, [
            'client_id'=>$client->id,
            'nom_client'=>$client->name,
            'nom_entreprise'=>$infosupp->nom_entreprise,
            'adresse_actuelle'=>'test',
            'nouvelle_adresse'=>'test',
            'informations_bagages'=> 'test',
            'date_demenagement' =>'2024-02-15'
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $updateRequest->assertStatus(200);
    }

    
}
