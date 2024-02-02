<?php

namespace Tests\Unit;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class ArticleUpdateTest extends TestCase
{
    use RefreshDatabase; // Pour réinitialiser la base de données après chaque test
    use WithFaker; // Pour utiliser des données factices

    public function testUserCanUpdateArticleSuccessfully()
    {
        // Création d'un utilisateur pour les besoins du test
        $user = User::create([
            'name' => 'Musa',
            'email' => 'musa@gmail.com',
             'password' => 'password',
             'telephone' => '+221774532345',
             'role' => 'Admin',
            'localite' => 'Dakar',
        ]);
        $article = Article::create(
            [
                'user_id'=>$user->id,
                'titre'=> 'titre 1',
                'contenu'=> 'contenue 1'
            ]
        );
        // dd($article);
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);
        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];
        // dd($token);
        $updatearticle = $this->actingAs($user)
        ->putJson('api/editarticle/'.$article->id, [
            'titre'=> 'titre 2',
            'contenu'=> 'contenue 2'
        ], [
            'authorization' => 'Bearer ' . $token,
        ]);
        $updatearticle->assertStatus(200);
    }

   
}
