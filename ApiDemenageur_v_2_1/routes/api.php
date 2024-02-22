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
use App\Models\Article;
use App\Models\Prestation;

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

/*
|----------------------------------------------------------------------------------------------------------------------
|                               Les routes publiques
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
|---------------------------------------------------------------------------------------------------------------------
|       AFFICHAGE ARTICLE
|       - Affichage des articles => /articlepost;
|       - Affichage d'un article =>/articlesinglepost/{article}
|       - Affichage des commentaires actifs d'un article => /actifcommentPostList/{article}
|       - Affichage des commentaires actifs d'une prestation => /actifcommentPrestation/{prestation}
|
*/
//------------------------------AFFICHAGE DE TOUS LES ARTICLES------------------------------------//
 Route::get('/articlepost', [ArticleController::class, 'index']);
//----------------------------------------------------------------------------------------------//
 Route::post('/register', [AuthController::class, 'register']);
 Route::post('/login', [AuthController::class, 'login']);

//-----------------------------REINITIALISER LE MOT DE PASSE-----------------------------------//
 Route::put('/resetpassword', [UserController::class, 'resetPassword']);
//------------------------------------------------------------------------------------------//
//-------------------------------AFFICHAGE D'UN ARTICLE------------------------------------------//
 Route::get('/articlesinglepost/{article}', [ArticleController::class, 'show']);
//---------------------------AFFICHAGE DES COMMENTAIRES ACTIFS D'UN ARTICLE---------------------------------//
 Route::get('/actifcommentPostList/{article}', [CommentaireController::class, 'activeCommentPost']);
//---------------------------------------------------------------------------------------------------------//
//---------------------------AFFICHAGE DES COMMENTAIRES ACTIFS D'UNE PRESTATION--------------------------------------//
 Route::get('/actifcommentPrestation/{prestation}', [CommentairePrestationController::class, 'actifCommentPrestation']);
//-------------------------------------------------------------------------------------------------------------------//




/*
|-----------------------------------------------------------------------------------------------------------------------
|                       Le middleware ['auth:api'] : Pour les connectés !
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
|       - Affichage de tous les offres actifs => /allactifsoffers
|       - Affichage de tous les offres inactifs => /allinactifsoffers
|       - Affichage de la liste des offres actifs d'un demenageur => /allactifoffersofonemover/{demenageur}
|       - Affichage de tous les offres inactifs d'un demenageur => /allinactifoffersofonemover/{demenageur}
|        
*/
Route::middleware(['auth:api'])->group(function (){
    //----------------------------------------Deconnexion--------------------------------------------------//
     Route::post('/logout', [AuthController::class, 'logout']);
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
    /**************************************************************************************************************
    *                           LES ROUTES D'AFFICHAGES [CONNECTED]
    **************************************************************************************************************/
    //----------------------------------AFFICHAGE INFORMATIONS USER---------------------------------------------//
     Route::get('/showuser/{user}', [UserController::class, 'show']);
    //-----------------------------------------------------------------------------------------------------------//
    //----------------------------------TOUS LES OFFRES ACTIFS---------------------------------------------//
     Route::get('/allactifsoffers', [OffreController::class, 'index']);
    //-----------------------------------------------------------------------------------------------------------//
    //----------------------------------TOUS LES OFFRES INACTIFS---------------------------------------------//
     Route::get('/allinactifsoffers', [OffreController::class, 'inactifOffers']);
    //-----------------------------------------------------------------------------------------------------------//
    //----------------------------------TOUS LES OFFRES ACTIFS D'UN DEMENAGEUR-----------------------------------//
     Route::get(' /allactifoffersofonemover/{demenageur}', [OffreController::class, 'allActifOfferOfOneMover']);
    //-----------------------------------------------------------------------------------------------------------//
    //----------------------------------TOUS LES OFFRES INACTIFS D'UN DEMENAGEUR---------------------------------//
     Route::get(' /allinactifoffersofonemover/{demenageur}', [OffreController::class, 'allInactifOfferOfOneMover']);
    //-----------------------------------------------------------------------------------------------------------//
    
});





/*
|--------------------------------------------------------------------------------------------------------------
|               Le middleware ['auth:api', 'role:Admin'] Admin
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
|       - Liste des articles inactifs => /inactifposts
|       - Liste des commentaires inactifs d'un article => /inactifcommentPostList/{article}
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
    //------------------------------ARTICLES INACTIFS----------------------------------------//
     Route::get('/inactifposts', [ArticleController::class, 'inactifPosts']);
    //----------------------------------------------------------------------------------------//
    //---------------------------AFFICHAGE DES COMMENTAIRES INACTIFS D'UN ARTICLE---------------------------------//
     Route::get('/inactifcommentPostList/{article}', [CommentaireController::class, 'inactiveCommentPost']);
    //----------------------------------------------------------------------------------------------------------//
});




/*
|---------------------------------------------------------------------------------------------------------------------
|                   Le middleware ['auth:api', 'role:Client'] Client
|---------------------------------------------------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
| Client comme :
|---------------------------------------------------------------------------------------------------------------------
|       CRUD DEMANDE DE DEVIS : 
|       - Ajouter demande de devis => demandedevisstore;
|       - Modifier demande de devis => demandedevisupdate/{demandeDevis};
|       - Désactivé demande de devis => demandedevisdesactivate/{demandeDevis};
|
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
|       - Annuler une prestation => /prestationcancel/{prestation} ;
|---------------------------------------------------------------------------------------------------------------------
|       INTEGRATION DE L'API WHATSAPP
|       - Gérer le chat entre les clients et le déménageur => /whatsappchat/{demenageur};
|---------------------------------------------------------------------------------------------------------------------
|       ROUTES D'AFFICHAGE
|       - Affichage des demandes de devis actifs d'un client => /demandedevisactifofcustomer/{customer}
|       - Affichage des demandes de devis inactifs d'un client => /demandedevisinactifofcustomer/{customer}
|       - Affichage de tous les demandes de devis  d'un client => /alldemandedevisactifofcustomer/{customer}
|       - Affichage des devis actifs d'un client => /devisactifofonecustomer/{customer}
|       - Affichage des devis inactifs d'un client => /devisinactifofonecustomer/{customer}
|       - Affichage de tous les devis d'un client => /alldevisofonecustomer/{customer}
|       - Affichage des souscriptions actifs d'un client => /souscriptionactifofonecustomer/{customer};
|       - Affichage des souscriptions inactifs d'un client => /souscriptioninactifofonecustomer/{customer};
|       - Affichage de tous les souscriptions d'un client => /allsouscriptionofonecustomer/{customer};
|       - Affichage des prestations actifs d'un client => /prestationactifofonecustomer/{customer};
|       - Affichage des prestations inactifs d'un client => /prestationinactifofonecustomer/{customer};
|       - Affichage de tous les prestations d'un client => /allprestationofonecustomer/{customer};
*/
Route::middleware(['auth:api','role:Client'])->group(function (){
    //-------------------------------------CRUD DEMANDE DE DEVIS---------------------------------------------------//
     Route::post('/demandedevisstore/{demenageur}', [DemandeDevisController::class, 'store']);
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
     Route::post('/devisvalidate/{devis}', [DevisController::class, 'valider']);
    //-------------------------------------------------------------------------------------------------------//
    //--------------------------------------VALIDER / REFUSER DEVIS--------------------------------------------//
     Route::post('/devisdeny/{devis}', [DevisController::class, 'refuser']);
    //-------------------------------------------------------------------------------------------------------//
    //------------------------------------CRUD COMMENTAIRE PRESTATION---------------------------------------//
        // Route::post('/commentpreststore/{prestation}', [CommentairePrestationController::class, 'store']);
        // Route::put('/commentprestupdate/{commentairePrestation}', [CommentairePrestationController::class, 'update']);
        // Route::put('/commentprestactivate/{commentairePrestation}', [CommentairePrestationController::class, 'activer']);
        // Route::put('/commentprestdesactivate/{commentairePrestation}', [CommentairePrestationController::class, 'desactiver']);
        // Route::delete('/commentprestdelete/{commentairePrestation}', [CommentairePrestationController::class, 'destroy']);
    //-----------------------------------------------------------------------------------------------------//
    //-------------------------------------CRUD COMMENTAIRE PRESTATION NEW--------------------------------------------//
        Route::put('/commentprestsend/{prestation}', [PrestationController::class, 'send']);
    //----------------------------------------------------------------------------------------------------------------//
    //---------------------------------------ANNULER UNE PRESTATION--------------------------------------------------//
       Route::post('/prestationcancel/{prestation}', [PrestationController::class, 'cancel']);
    //-------------------------------------------------------------------------------------------------------------//
    /**************************************************************************************************************
        *                                      UTILISATION DE L'API WHATSAPP
        *************************************************************************************************************/
    //------------------------------------------API WHATSAPP--------------------------------------------------------//
     Route::post('/whatsappchat/{demenageur}', [OffreController::class, 'chatwhatsapp']);
    //-------------------------------------------------------------------------------------------------------------//
    /**************************************************************************************************************
        *                                      ROUTES D'AFFICHAGE
        *************************************************************************************************************/
    //-----------------------------------DEMANDE DE DEVIS ACTIFS D'UN CLIENT--------------------------------------------------//
     Route::get('/demandedevisactifofcustomer/{customer}', [DemandeDevisController::class, 'demandeDevisActifOfOneCustomer']);
    //-------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------DEMANDE DE DEVIS SPECIFIQUE D'UN CLIENT--------------------------------------------------//
    Route::get('/onedemandedevisofonecustomer/{demandeDevis}', [DemandeDevisController::class, 'oneDemandeDevisActifOfOneCustomer']);
    //-------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------DEMANDE DE DEVIS INACTIFS D'UN CLIENT-------------------------------------------------//
     Route::get('/demandedevisinactifofcustomer/{customer}', [DemandeDevisController::class, 'demandeDevisInactifOfOneCustomer']);
    //---------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------TOUS LES DEMANDES DE DEVIS D'UN CLIENT-------------------------------------------------//
     Route::get('/alldemandedevisactifofcustomer/{customer}', [DemandeDevisController::class, 'AlldemandeDevisOfOneCustomer']);
    //--------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------DEVIS ACTIFS D'UN CLIENT--------------------------------------------------//
     Route::get('/devisactifofonecustomer/{customer}', [DevisController::class, 'devisActifOfOneCustomer']);
    //--------------------------------------------------------------------------------------------------------------//
    //-----------------------------------DETAILS D'UN DEVIS ACTIF D'UN CLIENT--------------------------------------------------//
    Route::get('/onedevisactifofonecustomer/{devis}', [DevisController::class, 'onedevisactifofonecustomer']);
    //--------------------------------------------------------------------------------------------------------------//
    //-----------------------------------DEVIS INACTIFS D'UN CLIENT-------------------------------------------------//
     Route::get('/devisinactifofonecustomer/{customer}', [DevisController::class, 'devisInactifOfOneCustomer']);
    //-------------------------------------------------------------------------------------------------------------//
    //-----------------------------------TOUS LES DEVIS D'UN CLIENT-------------------------------------------------//
     Route::get('/alldevisofonecustomer/{customer}', [DevisController::class, 'allDevisOfOneCustomer']);
    //------------------------------------------------------------------------------------------------------------//
    //-----------------------------------SOUSCRIPTIONS ACTIFS D'UN CLIENT----------------------------------------//
     Route::get('/souscriptionactifofonecustomer/{customer}', [SouscriptionController::class, 'souscriptionActifOfOneCustomer']);
    //------------------------------------------------------------------------------------------------------//
    //-----------------------------------SOUSCRIPTIONS INACTIFS D'UN CLIENT--------------------------------------------------------//
     Route::get('/souscriptioninactifofonecustomer/{customer}', [SouscriptionController::class, 'souscriptionInactifOfOneCustomer']);
    //----------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------TOUS SOUSCRIPTIONS D'UN CLIENT----------------------------------------------------//
     Route::get('/allsouscriptionofonecustomer/{customer}', [SouscriptionController::class, 'allSouscriptionOfOneCustomer']);
    //-------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------PRESTATIONS ACTIFS D'UN CLIENT----------------------------------------//
     Route::get('/prestationactifofonecustomer/{customer}', [PrestationController::class, 'prestationActifOfOneCustomer']);
    //------------------------------------------------------------------------------------------------------//
    //-----------------------------------PRESTATIONS INACTIFS D'UN CLIENT--------------------------------------------------------//
     Route::get('/prestationinactifofonecustomer/{customer}', [PrestationController::class, 'prestationInactifOfOneCustomer']);
    //----------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------TOUS PRESTATIONS D'UN CLIENT----------------------------------------------------//
     Route::get('/allprestationofonecustomer/{customer}', [PrestationController::class, 'allPrestationOfOneCustomer']);
    //-------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------INFORMATIONS SUPPLEMENTAIRES DES DEMENAGEURS----------------------------------------------------//
    Route::get('/allinformationsuppofallmover', [InformationsSuppController::class, 'allInformationsSuppOfAllMovers']);
    //-------------------------------------------------------------------------------------------------------------------//
});





/*
|--------------------------------------------------------------------------------------------------------------------
|               Le middleware ['auth:api', 'role:Demenageur'] Déménageur
|--------------------------------------------------------------------------------------------------------------------
|
| Ce middleware contient les routes des utilisateurs connectés en tant que
|               Demenageur comme :
|--------------------------------------------------------------------------------------------------------------------
|       AFFICHAGE DEMANDE DE DEVIS :
|       - Afficher les demandes de devis actifs d'un déménageur => /demandedevisactiflistofonemover/{demenageur};
|       - Afficher les demandes de devis inactifs d'un déménageur => /demandedevisinactiflistofonemover/{demenageur};
|       - Afficher tous les demandes de devis  d'un déménageur => /alldemandedevislistofonemover/{demenageur};
|       - Afficher les informations supplémentaires d'un déménageur => /allinfosuppofonemover/{demenageur};
|       - Afficher les devis actifs d'un déménageur => /devisactifofonemover/{demenageur};
|       - Afficher les devis inactifs d'un déménageur => /devisinactifofonemover/{demenageur};
|       - Afficher tous les devis d'un déménageur => /alldevisofonemover/{demenageur};
|       - Afficher les souscriptions actifs d'un déménageur => /souscriptionactifofonemover/{offre};
|       - Afficher les souscriptions inactifs d'un déménageur => /souscriptioninactifofonemover/{offre};
|       - Afficher tous les souscriptions d'un déménageur => /allsouscriptionofonemover/{offre};
|       - Afficher les prestations actifs d'un déménageur => /prestationactifofonemover/{demenageur};
|       - Afficher les prestations inactifs d'un déménageur => /prestationinactifofonemover/{demenageur};
|       - Afficher tous les prestations d'un déménageur => /allprestationofonemover/{demenageur};
|
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
    //------------------------------------------REFUSER SOUSCRIPTION----------------------------------------------//
     Route::post('/souscriptiondeny/{souscription}', [SouscriptionController::class, 'refuser']);
    //-----------------------------------------------------------------------------------------------------------//
    /**************************************************************************************************************
        *                                     ROUTES D'AFFICHAGE
        *************************************************************************************************************/
    //--------------------------------------------DEMANDE DE DEVIS ACTIFS D'UN DEMENAGEUR--------------------------------------------------------------------//
     Route::get('/demandedevisactiflistofonemover/{demenageur}', [DemandeDevisController::class, 'demandeDevisActifOfOneMover']);
    //------------------------------------------------------------------------------------------------------------------------------------------------------//
     //-----------------------------------UNE DEMANDE DE DEVIS SPECIFIQUE D'UN DEMENAGEUR------------------------------------------------------------------//
     Route::get('/demandedevisactiflistofonedemandeofonemover/{demandeDevis}', [DemandeDevisController::class, 'oneDemandeDevisActifOfOneMover']);
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------DEMANDE DE DEVIS INACTIFS D'UN DEMENAGEUR----------------------------------------------------------------------//
     Route::get('/demandedevisinactiflistofonemover/{demenageur}', [DemandeDevisController::class, 'demandeDevisInactifOfOneMover']);
    //--------------------------------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------TOUS LES DEMANDES DE DEVIS D'UN DEMENAGEUR-------------------------------------------------------------------//
     Route::get('/alldemandedevislistofonemover/{demenageur}', [DemandeDevisController::class, 'allDemandeDevisOfOneMover']);
    //------------------------------------------------------------------------------------------------------------------------------------------------//
    //-------------------------------------INFORMATIONS SUPP D'UN DEMENAGEUR-------------------------------------------------------------------------//
     Route::get('/allinfosuppofonemover/{demenageur}', [InformationsSuppController::class, 'show']);
    //----------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------DEVIS ACTIFS D'UN DEMENAGEUR-------------------------------------------------------------------//
     Route::get('/devisactifofonemover/{demenageur}', [DevisController::class, 'devisActifOfOneMover']);
    //--------------------------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------------DEVIS INACTIFS D'UN DEMENAGEUR--------------------------------------------------------------------//
     Route::get('/devisinactifofonemover/{demenageur}', [DevisController::class, 'devisInactifOfOneMover']);
    //------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------TOUS DEVIS D'UN DEMENAGEUR-----------------------------------------------------------------------//
     Route::get('/alldevisofonemover/{demenageur}', [DevisController::class, 'allDevisOfOneMover']);
    //----------------------------------------------------------------------------------------------------------------------------------------//
    //--------------------------------------------SOUSCRIPTIONS ACTIFS D'UN DEMENAGEUR-------------------------------------------------------//
     Route::get('/souscriptionactifofonemover/{offre}', [SouscriptionController::class, 'souscriptionActifOfOneMover']);
    //--------------------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------SOUSCRIPTIONS INACTIFS D'UN DEMENAGEUR------------------------------------------------------------//
     Route::get('/souscriptioninactifofonemover/{offre}', [SouscriptionController::class, 'souscriptionInactifOfOneMover']);
    //------------------------------------------------------------------------------------------------------------------------------------//
    //--------------------------------------------TOUS SOUSCRIPTIONS D'UN DEMENAGEUR-----------------------------------------------------//
     Route::get('/allsouscriptionofonemover/{offre}', [SouscriptionController::class, 'allSouscriptionOfOneMover']);
    //----------------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------PRESTATIONS ACTIFS D'UN DEMENAGEUR------------------------------------------------------------//
     Route::get('/prestationactifofonemover/{demenageur}', [PrestationController::class, 'prestationActifOfOneMover']);
    //--------------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------PRESTATIONS INACTIFS D'UN DEMENAGEUR--------------------------------------------------------//
     Route::get('/prestationinactifofonemover/{demenageur}', [PrestationController::class, 'prestationInactifOfOneMover']);
    //----------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------TOUS PRESTATIONS D'UN DEMENAGEUR-------------------------------------------------------//
     Route::get('/allprestationofonemover/{demenageur}', [PrestationController::class, 'allPrestationOfOneMover']);
    //------------------------------------------------------------------------------------------------------------------------//
});
