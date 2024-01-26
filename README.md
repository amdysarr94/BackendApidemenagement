
# BackendApidemenagement

Cet Api est pour une plateforme de mise en relation entre déménageurs et particuliers. L'application permet aux clients de planifier leur déménagement, d'obtenir des devis, et de communiquer avec les déménageurs.
______________________________________________________________________________________________________________________________________________________________________________________



## Descriptions : 

Ce repository contient le code source complet d'une application de mise en relation entre déménageurs et particuliers, offrant une plateforme complète pour la gestion des services de déménagement. L'application compte trois types d'utilisateurs : l'administrateur, les déménageurs et les clients. 
## Fonctionnalités Principales 
_________________________________
### Administrateur 
            • Gestion des comptes des déménageurs et des clients. 
            • Possibilité de désactiver des comptes en cas de besoin. 
            • Responsabilité du blog de conseils du site, avec la possibilité d'ajouter, modifier ou supprimer des articles. 
  ### Déménageur 
              • Espace personnel pour la gestion des offres (ajout, modification, suppression). 
              • Validation ou invalidation des devis des clients. 
              • Modification des informations du compte, y compris la désactivation du compte. 
 ###   Client 
              • Consultation de la liste des déménageurs actifs dans sa localité. 
              • Planification de la date du déménagement. 
              • Demande de devis en fournissant les détails du déménagement (nombre de meubles, distances, lieux de départ et d'arrivée, etc.).
              • Acceptation ou annulation de la facture proposée par le déménageur. 
              • Notification par mail au déménageur en cas d'acceptation de la facture. 
              • Possibilité de laisser des commentaires sur le déménageur après la prestation. Fonctionnalités Avancées 
              • Intégration de l'API WhatsApp pour une communication directe entre les clients et les déménageurs. 
              • Commentaires sur les articles du blog de conseils. 
  ###  Technologies Utilisées 
        • Frontend : 
        • Backend : Laravel 10
        • Base de données : MySQL
-----------------------------------------------------------------------------------------
### Note : Assurez-vous de consulter la documentation fournie pour obtenir des informations détaillées sur l'utilisation et la personnalisation de l'application. Nous vous encourageons également à participer à l'amélioration de cette plateforme en soumettant des problèmes et des demandes de fusion.
## Installer et démarrer l'Api
------------------------------------------------------------------------------------------
1) Installer composer sur sa machine, si c'est pas déjà fait.

    • site : https://getcomposer.org/

3) Créer un fichier .env

4) Copier le contenu du  fichier .env.example qui se trouve dans le répertoire courant du projet dans le nouveau fichier .env créé.

5) Installer composer sur le projet, en tapant la commande : 
```bash
  composer install
```
5) Définir le nom de la  base de données dans le fichier .env 

6) Effectuer une migration
```bash
  php artisan migrate
```
7) Executer un seeder pour créer un Admin 
```bash
  php artisan db:seed
```
8) Générer la clé Jwt
```bash
  php artisan jwt:secret
```
9) Activer le server pour run le projet laravel avec : 
```bash
  php artisan serve
```
