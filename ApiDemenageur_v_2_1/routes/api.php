<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\DemandeDevisController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
|--------------------------------------------------------------------------
| Les routes publiques
|--------------------------------------------------------------------------
|
| Les routes publiques sont accessibles a toutes les users connectés ou 
| pas comme :
|       - L'authentification register => register;
|       - La connexion login => login;
|       - Réinitialisation du mot de passe;
|       - Affichage des articles paginés;
|       - Affichage des commentaires des articles;
*/
// ------------------------------Affichage article------------------------------------//

// ----------------------------------------------------------------------------------//
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Le middleware ['auth:api']
|--------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés comme :
|       - La déconnexion logout;
| -------------------------------------------------------------------------
|           CRUD COMMENTAIRE ARTICLE
|       - Ajouter un commentaire => storecommentarticle;
|       - Modifier un commentaire => updatecommentarticle/{commentaire};
|       - Activer un commentaire => activatecommentarticle/{commentaire};
|       - Désactiver un commentaire => desactivatecommentarticle/{commentaire};
|       - Supprimer un commentaire => deletecommentarticle/{commentaire};
*/
Route::middleware(['auth:api'])->group(function (){
    //----------------------------------------Deconnexion--------------------------------------------------//
    Route::post('logout', [AuthController::class, 'logout']);
    //---------------------------------------------------------------------------------------------------//
    //--------------------------------CRUD COMMENTAIRE ARTICLE------------------------------------------//
    Route::post('/storecommentarticle/{article}', [CommentaireController::class, 'store']);
    Route::put('/updatecommentarticle/{commentaire}', [CommentaireController::class, 'update']);
    Route::put('/activatecommentarticle/{commentaire}', [CommentaireController::class, 'activate']);
    Route::put('/desactivatecommentarticle/{commentaire}', [CommentaireController::class, 'desactivate']);
    Route::delete('/deletecommentarticle/{commentaire}', [CommentaireController::class, 'destroy']);
    //-------------------------------------------------------------------------------------------------//
});
/*
|--------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Admin']
|--------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Admin comme :
|-------------------------------------------------------------------------
|           CRUD ARTICLE
|       - Ajouter un article =>storearticle;
|       - Modifier un article => editarticle;
|       - Activer et désactiver un article => activatearticle et 
|           desactivatearticle;
|-------------------------------------------------------------------------
|           CRUD ROLE
|       - Ajouter un role => storerole;
|       - Modifier un role  => updaterole;
|       - Supprimer un role => deleterole;
*/
Route::middleware(['auth:api','role:Admin'])->group(function (){
    //---------------------------CRUD article--------------------------------------------//
    Route::post('/storearticle', [ArticleController::class, 'store']);
    Route::put('/editarticle/{article}', [ArticleController::class, 'update']);
    Route::put('/activatearticle/{article}', [ArticleController::class, 'activate']);
    Route::put('/desactivatearticle/{article}', [ArticleController::class, 'desactivate']);
    //-----------------------------------------------------------------------------------//
    //---------------------------CRUD role----------------------------------------------//
    Route::post('/storerole', [RoleController::class, 'store']);
    Route::put('/updaterole/{role}', [RoleController::class, 'update']);
    Route::delete('/deleterole/{role}', [RoleController::class, 'destroy']);
    //-----------------------------------------------------------------------------------//
});
/*
|--------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Client']
|--------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Client comme :
|------------------------------------------------------------------------------
|       CRUD DEMANDE DE DEVIS : 
|       - Ajouter demande de devis => demandedevisstore;
|       - Modifier demande de devis => demandedevisupdate/{demandeDevis};
|       - Désactivé demande de devis => demandedevisdesactivate/{demandeDevis};
|-------------------------------------------------------------------------------
|       AFFICHAGE DEMANDE DE DEVIS :
|       - Afficher les demandes de devis;
|    
*/
Route::middleware(['auth:api','role:Client'])->group(function (){
     //-------------------------------------CRUD DEMANDE DE DEVIS--------------------------------------------//
     Route::post('/demandedevisstore', [DemandeDevisController::class, 'store']);
     Route::put('/demandedevisupdate/{demandeDevis}', [DemandeDevisController::class, 'update']);
     Route::put('/demandedevisdesactivate/{demandeDevis}', [DemandeDevisController::class, 'desactivate']);
     //-----------------------------------------------------------------------------------------------------//
});
/*
|--------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Demenageur']
|--------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Demenageur comme :
|--------------------------------------------------------------------------
|       AFFICHAGE DEMANDE DE DEVIS :
|       - Afficher les demandes de devis;
| 
|
*/
Route::middleware(['auth:api','role:Demenageur'])->group(function (){

});