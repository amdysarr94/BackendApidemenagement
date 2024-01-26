<?php

use App\Models\Souscription;
use Illuminate\Http\Request;
use App\Models\InformationsSupp;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\CommentairePrestationController;
use App\Http\Controllers\DemandeDevisController;
use App\Http\Controllers\SouscriptionController;
use App\Http\Requests\SouscriptionUpdateRequest;
use App\Http\Controllers\InformationsSuppController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\UserController;

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
|----------------------------------------------------------------------------------------------------------------------
| Les routes publiques
|---------------------------------------------------------------------------------------------------------------------
|
| Les routes publiques sont accessibles a toutes les users connectés ou  pas comme :
|       - L'authentification register => register;
|       - La connexion login => login;
|       - Réinitialisation du mot de passe;
|       - Affichage des articles paginés;
|       - Affichage des commentaires des articles;
|--------------------------------------------------------------------------------------------------------------------  
|      REINITIALISER LE MOT DE PASSE
|       - réinitialiser le mot de passe => resetpassword;
|
*/
// ------------------------------AFFICHAGE ARTICLE------------------------------------//

// ----------------------------------------------------------------------------------//
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//-----------------------------REINITIALISER LE MOT DE PASSE-----------------------------------//
Route::put('resetpassword', [UserController::class, 'resetPassword']);
//------------------------------------------------------------------------------------------//





/*
|-----------------------------------------------------------------------------------------------------------------------
| Le middleware ['auth:api'] : Pour les connectés !
|----------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------
| Exigence : [Vérification du role]
|----------------------------------------------------------------------------------------------------------------------
| Ce middleware contient les routes des utilisateurs connectés comme :
|       - La déconnexion logout;
|---------------------------------------------------------------------------------------------------------------------
|           CRUD COMMENTAIRE ARTICLE
|       - Ajouter un commentaire => storecommentarticle;
|       - Modifier un commentaire => updatecommentarticle/{commentaire};
|       - Activer un commentaire => activatecommentarticle/{commentaire};
|       - Désactiver un commentaire => desactivatecommentarticle/{commentaire};
|       - Supprimer un commentaire => deletecommentarticle/{commentaire};
|--------------------------------------------------------------------------------------------------------------------
|       MODIFIER LES INFORMATIONS DE PROFILS
|       - modifier les information de compte => /editprofil;
|-------------------------------------------------------------------------------------------------------------------
|       ROUTES D'AFFICHAGE
|       - Affichage des informations d'un user => /showuser/{user}
|        
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
    //----------------------------------MODIFIER LES INFORMATIONS DE PROFILS---------------------------------------//
    Route::put('/editprofil/{user}', [UserController::class, 'update']);
    //-----------------------------------------------------------------------------------------------------------//
    /**********************************************************************************************
    *                           LES ROUTES D'AFFICHAGES [CONNECTED]
    *********************************************************************************************/
    //----------------------------------AFFICHAGE INFORMATIONS USER---------------------------------------------//
    Route::get('/showuser/{user}', [UserController::class, 'show']);
    //-----------------------------------------------------------------------------------------------------------//
    
});





/*
|--------------------------------------------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Admin'] Admin
|--------------------------------------------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Admin comme :
|-------------------------------------------------------------------------------------------------------------
|           CRUD ARTICLE
|       - Ajouter un article =>storearticle;
|       - Modifier un article => editarticle;
|       - Activer et désactiver un article => activatearticle et 
|           desactivatearticle;
|       - Supprimer un article => deletearticle/{article}
|-------------------------------------------------------------------------------------------------------------
|           CRUD ROLE
|       - Ajouter un role => storerole;
|       - Modifier un role  => updaterole;
|       - Supprimer un role => deleterole;
|------------------------------------------------------------------------------------------------------------
|           Route d'affichage :
|           UTILISATEURS ACTIFS
|       - Liste de tous les users actifs => /allactifuser
|       - Liste de tous les users inactifs => /allinactifuser
|       - Liste de tous les clients => /allcustomers
|       - Liste de tous les déménageurs => /allmovers
*/
Route::middleware(['auth:api','role:Admin'])->group(function (){
    /****************************************************************************************
    *                               LES CRUD [ADMIN]
    ***************************************************************************************/
    //---------------------------CRUD article--------------------------------------------//
    Route::post('/storearticle', [ArticleController::class, 'store']);
    Route::put('/editarticle/{article}', [ArticleController::class, 'update']);
    Route::put('/activatearticle/{article}', [ArticleController::class, 'activate']);
    Route::put('/desactivatearticle/{article}', [ArticleController::class, 'desactivate']);
    Route::delete('/deletearticle/{article}', [ArticleController::class, 'destroy']);
    //-----------------------------------------------------------------------------------//
    //---------------------------CRUD role----------------------------------------------//
    Route::post('/storerole', [RoleController::class, 'store']);
    Route::put('/updaterole/{role}', [RoleController::class, 'update']);
    Route::delete('/deleterole/{role}', [RoleController::class, 'destroy']);
    //-----------------------------------------------------------------------------------//
    //-----------------------------------CRUD demande de devis-------------------------------------//
    Route::put('/demandedevissuppress/{demandeDevis}', [DemandeDevisController::class, 'destroy']);
    //--------------------------------------------------------------------------------------------//
    /**********************************************************************************************
    *                           LES ROUTES D'AFFICHAGES [ADMIN]
    *********************************************************************************************/
    //-------------------------------UTILISATEURS ACTIFS---------------------------------------//
    Route::get('/alluseractif', [UserController::class, 'allActifUser']);
    //----------------------------------------------------------------------------------------//
    //--------------------------------UTILISATEURS INACTIFS------------------------------------//
    Route::get('/allinactifuser', [UserController::class, 'allInactifUser']);
    //----------------------------------------------------------------------------------------//
    //---------------------------------UTILISATEURS CLIENTS-----------------------------------//
    Route::get('/allcustomers', [UserController::class, 'allCustomerUser']);
    //----------------------------------------------------------------------------------------//
    //------------------------------UTILISATEURS DEMENAGEURS-----------------------------------//
    Route::get('/allmovers', [UserController::class, 'allMoverUser']);
    //----------------------------------------------------------------------------------------//
});




/*
|---------------------------------------------------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Client'] Client
|---------------------------------------------------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Client comme :
|---------------------------------------------------------------------------------------------------------------------
|       CRUD DEMANDE DE DEVIS : 
|       - Ajouter demande de devis => demandedevisstore;
|       - Modifier demande de devis => demandedevisupdate/{demandeDevis};
|       - Désactivé demande de devis => demandedevisdesactivate/{demandeDevis};
|---------------------------------------------------------------------------------------------------------------------
|       AFFICHAGE DEMANDE DE DEVIS :
|       - Afficher les demandes de devis;
|---------------------------------------------------------------------------------------------------------------------
|       CRUD SOUSCRIPTION
|       - Ajouter une souscription => souscriptionstore/{offre};
|       - Modifier une souscription => souscriptionupdate/{souscription};
|       - Activer une souscription => souscriptionactivate/{souscription};
|       - Désactiver une souscription => souscriptiondesactivate/{souscription};
|       - Supprimer une souscription => souscriptiondelete/{souscription};
|---------------------------------------------------------------------------------------------------------------------
|           VALIDER / REFUSER DEVIS :
|       - valider devis => devisvalidate/{devis} [Vérification : auth()->user()->name == devis->nom_client]
|       - refuser devis => devisdeny{devis}      [Vérification : auth()->user()->name == devis->nom_client]
|---------------------------------------------------------------------------------------------------------------------
|       CRUD COMMENTAIRE PRESTATION : 
|       - Ajouter un commentaire pour une prestation => /commentpreststore/{prestation};
|       - Modifier un commentaire pour une prestation => /commentprestupdate/{commentairePrestation};
|       - activer un commentaire pour une prestation => /souscriptionactivate/{commentairePrestation};
|       - désactiver un commentaire pour une prestation => /souscriptiondesactivate/{commentairePrestation};
|       - Supprimer un commentaire pour une prestation => /souscriptiondelete/{commentairePrestation};
|----------------------------------------------------------------------------------------------------------------------
|       ANNULER UNE PRESTATION
|       - Annuler une prestation ;
*/
Route::middleware(['auth:api','role:Client'])->group(function (){
     //-------------------------------------CRUD DEMANDE DE DEVIS---------------------------------------------------//
     Route::post('/demandedevisstore', [DemandeDevisController::class, 'store']);
     Route::put('/demandedevisupdate/{demandeDevis}', [DemandeDevisController::class, 'update']);
     Route::put('/demandedevisdesactivate/{demandeDevis}', [DemandeDevisController::class, 'desactivate']);
     //------------------------------------------------------------------------------------------------------------//
     //---------------------------------------CRUD SOUSCRIPTION---------------------------------------------------//
     Route::post('/souscriptionstore/{offre}', [SouscriptionController::class, 'store']);
     Route::put('/souscriptionupdate/{souscription}', [SouscriptionController::class, 'update']);
     Route::put('/souscriptionactivate/{souscription}', [SouscriptionController::class, 'activate']);
     Route::put('/souscriptiondesactivate/{souscription}', [SouscriptionController::class, 'desactivate']);
     Route::put('/souscriptiondelete/{souscription}', [SouscriptionController::class, 'destroy']);
     //----------------------------------------------------------------------------------------------------------//
     //--------------------------------------VALIDER / REFUSER DEVIS--------------------------------------------//
     Route::put('/devisvalidate/{devis}', [DevisController::class, 'valider']);
     //-------------------------------------------------------------------------------------------------------//
     //------------------------------------CRUD COMMENTAIRE PRESTATION---------------------------------------//
     Route::post('/commentpreststore/{prestation}', [CommentairePrestationController::class, 'store']);
     Route::put('/commentprestupdate/{commentairePrestation}', [CommentairePrestationController::class, 'update']);
     Route::put('/commentprestactivate/{commentairePrestation}', [CommentairePrestationController::class, 'activer']);
     Route::put('/commentprestdesactivate/{commentairePrestation}', [CommentairePrestationController::class, 'desactiver']);
     Route::delete('/commentprestdelete/{commentairePrestation}', [CommentairePrestationController::class, 'destroy']);
     //-----------------------------------------------------------------------------------------------------//
     //---------------------------------------ANNULER UNE PRESTATION--------------------------------------------------//
     Route::post('/prestationcancel/{prestation}', [PrestationController::class, 'cancel']);
     //-------------------------------------------------------------------------------------------------------------//
});





/*
|--------------------------------------------------------------------------------------------------------------------
| Le middleware ['auth:api', 'role:Demenageur'] Déménageur
|--------------------------------------------------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
|               Demenageur comme :
|--------------------------------------------------------------------------------------------------------------------
|       AFFICHAGE DEMANDE DE DEVIS :
|       - Afficher les demandes de devis;
|       - Afficher les devis;
|--------------------------------------------------------------------------------------------------------------------
|       CRUD OFFRE : 
|      - Ajouter une offre => offrestore;
|      - Modifier une offre => offreupdate;
|      - Activer une offre => offreactivate;
|      - Désactiver une offre => offredesactivate;
|      - Supprimer une offre => deleteoffre;
|---------------------------------------------------------------------------------------------------------------------
|       CRUD INFORMATIONS SUPPLEMENTAIRES :
|      - Ajouter les informations supplémentaires => infosupstore;
|      - Modifier les informations supplémentaires => infosupupdate;
|      - Activer les informations supplémentaires => infosupactivate;
|      - Désactiver les informations supplémentaires => infosupdesactivate;
|      - Supprimer les informations supplémentaires => infosupdelete;
|-------------------------------------------------------------------------------------------------------------------
|       CRUD DEVIS :
|       - Ajouter un devis => devisstore/{demandeDevis};
|       - Modifier un devis => devisupdate/{devis};
|       - Activer un devis => devisactivate/{devis};
|       - Désactiver un devis => devisdesactivate/{devis};
|       - Supprimer un devis => devisdelete/{devis};
|--------------------------------------------------------------------------------------------------------------------
|       VALIDER SOUSCRIPTION : 
|      - valider une souscription => souscriptionvalidate/{souscription}
|
*/
Route::middleware(['auth:api','role:Demenageur'])->group(function (){
    //-----------------------------------CRUD OFFRE--------------------------------------//
    Route::post('/offrestore', [OffreController::class, 'store']);
    Route::put('/offreupdate/{offre}', [OffreController::class, 'update']);
    Route::put('/offreactivate/{offre}', [OffreController::class, 'activate']);
    Route::put('/offredesactivate/{offre}', [OffreController::class, 'desactivate']);
    Route::delete('/deleteoffre/{offre}', [OffreController::class, 'destroy']);
    //----------------------------------------------------------------------------------//
    //--------------------------------CRUD INFORMATIONS SUPPLEMENTAIRES--------------------------------------------//
    Route::post('/infosupstore', [InformationsSuppController::class, 'store']);
    Route::put('/infosupupdate/{informationsSupp}', [InformationsSuppController::class, 'update']);
    Route::put('/infosupactivate/{informationsSupp}', [InformationsSuppController::class, 'activate']);
    Route::put('/infosupdesactivate/{informationsSupp}', [InformationsSuppController::class, 'desactivate']);
    Route::delete('/infosupdelete/{informationsSupp}', [InformationsSuppController::class, 'destroy']);
    //------------------------------------------------------------------------------------------------------------//
    //-----------------------------------------------CRUD DEVIS---------------------------------------------------//
    Route::post('/devisstore/{demandeDevis}',[DevisController::class, 'store']);
    Route::put('/devisupdate/{devis}',[DevisController::class, 'update']);
    Route::put('/devisactivate/{devis}',[DevisController::class, 'activate']);
    Route::put('/devisdesactivate/{devis}',[DevisController::class, 'desactivate']);
    Route::delete('/devisdelete/{devis}',[DevisController::class, 'destroy']);
    //-------------------------------------------------------------------------------------------------------------//
    //------------------------------------------VALIDER SOUSCRIPTION----------------------------------------------//
    Route::post('/souscriptionvalidate/{souscription}', [SouscriptionController::class, 'valider']);
    //-----------------------------------------------------------------------------------------------------------//
});