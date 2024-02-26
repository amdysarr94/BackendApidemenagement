<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        // Personnalisations des exceptions :
        $this->reportable(function (\Illuminate\Database\QueryException $e) {
            Log::error("Une exception de base de données s'est produite: " . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur de base de données s\'est produite. Veuillez réessayer plus tard.'
            ], 500);
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'error' => 'Vous ne pouvez effectuer cette action.',
                'details' => 'La method utiliser n\est pas supporter',
                'url' => 'Cette route ' . ' ' . $request->url() . ' ' . 'Supporte pas la methode utiliser',
            ], 405);
        });
        $this->renderable(function (RouteNotFoundException $e, $request) {
            return response()->json([
                'error' => 'Veuillez vous connecter svp!!!.',
                'details' => 'Soit Vous n\'etes pas connecter soit vous n\'avez les droit d\'acces necessaire',
                'url' => 'Cette route ' . ' ' . $request->url() . ' ' . 'vous est interdite',
            ], 401);
        });
        $this->reportable(function (\Illuminate\Auth\AuthenticationException $e) {
            Log::error("Une erreur d'authentification s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Validation\ValidationException $e) {
            Log::error("Une exception de validation s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Une exception de modèle non trouvée s'est produite: " . $e->getMessage());
        });

        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            return response()->json([
                'message' => 'Erreur de base de données !',
                'details' => $e->getMessage(),
                'url' => $request->url()
            ], 500);
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'error' => 'Erreur d\'authentification !',
                'details' => $e->getMessage(),
                'url' => $request->url()
            ], 401);
        });

        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'error' => 'Les données renseignées sont invalides !',
                'details' => $e->errors(),
                'url' => $request->url()
            ], 422);
        });

        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => 'Erreur de modèle non trouvée !',
                'details' => $e->getMessage(),
                'url' => $request->url()
            ], 404);
        });
        $this->reportable(function (\Illuminate\Database\QueryException $e) {
            Log::error("Une exception de base de données s'est produite: " . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur de base de données s\'est produite.'
            ], 500);
        });

        $this->reportable(function (\Illuminate\Auth\AuthenticationException $e) {
            Log::error("Une erreur d'authentification s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Validation\ValidationException $e) {
            Log::error("Une exception de validation s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Une exception de modèle non trouvée s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            Log::error("Une exception de fichier non trouvé s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            Log::error("Une exception de route non trouvée s'est produite: " . $e->getMessage());
        });


        $this->reportable(function (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            Log::error("Une exception de méthode HTTP non autorisée s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Session\TokenMismatchException $e) {
            Log::error("Une exception de token CSRF invalide s'est produite: " . $e->getMessage());
        });
    }
}
