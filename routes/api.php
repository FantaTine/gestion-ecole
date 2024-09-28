<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserFirebaseController;
use App\Http\Controllers\API\AuthFirebaseController;
use App\Http\Controllers\API\PromotionFirebaseController;
use App\Http\Controllers\API\ReferentielFirebaseController;
use App\Http\Controllers\API\ApprenantFirebaseController;

Route::prefix('v1')->group(function () {
    // Route d'authentification
    Route::post('/auth/login', [AuthFirebaseController::class, 'login']);

    // Routes pour les utilisateurs
    Route::get('/users', [UserFirebaseController::class, 'index']);
    Route::get('/users/{id}', [UserFirebaseController::class, 'show']);
    Route::post('/users', [UserFirebaseController::class, 'create']);
    Route::put('/users/{id}', [UserFirebaseController::class, 'update']);
    Route::delete('/users/{id}', [UserFirebaseController::class, 'delete']);
    Route::get('/users/email/{email}', [UserFirebaseController::class, 'getUserByEmail']);
    Route::get('/users/telephone/{phone}', [UserFirebaseController::class, 'getUserByPhone']);

    // Nouvelles routes pour les référentiels
    Route::post('/referentiels', [ReferentielFirebaseController::class, 'create']);
    Route::get('/referentiels', [ReferentielFirebaseController::class, 'index']);
    Route::get('/referentiels/{id}', [ReferentielFirebaseController::class, 'show']);
    Route::patch('/referentiels/{id}', [ReferentielFirebaseController::class, 'update']);
    Route::delete('/referentiels/{id}', [ReferentielFirebaseController::class, 'delete']);
    Route::get('/archive/referentiels', [ReferentielFirebaseController::class, 'getArchived']);

    // Nouvelles routes pour les compétences et modules
    Route::post('/referentiels/{id}/competences', [ReferentielFirebaseController::class, 'addCompetence']);
    Route::post('/referentiels/{referentielId}/competences/{competenceId}/modules', [ReferentielFirebaseController::class, 'addModule']);
    Route::delete('/referentiels/{referentielId}/competences/{competenceId}', [ReferentielFirebaseController::class, 'deleteCompetence']);
    Route::delete('/referentiels/{referentielId}/competences/{competenceId}/modules/{moduleId}', [ReferentielFirebaseController::class, 'deleteModule']);

    // Routes pour les promotions
    Route::post('/promotions', [PromotionFirebaseController::class, 'create']);
    Route::patch('/promotions/{id}', [PromotionFirebaseController::class, 'update']);
    Route::patch('/promotions/{id}/referentiels', [PromotionFirebaseController::class, 'updateReferentiels']);
    Route::patch('/promotions/{id}/etat', [PromotionFirebaseController::class, 'updateEtat']);
    Route::get('/promotions', [PromotionFirebaseController::class, 'index']);
    Route::get('/promotions/encours', [PromotionFirebaseController::class, 'encours']);
    Route::patch('/promotions/{id}/cloturer', [PromotionFirebaseController::class, 'cloturer']);
    Route::get('/promotions/{id}/referentiels', [PromotionFirebaseController::class, 'getReferentiels']);
    Route::get('/promotions/{id}/stats', [PromotionFirebaseController::class, 'getStats']);

    // Routes pour les apprenants
    Route::post('/apprenants', [ApprenantFirebaseController::class, 'store']);
    Route::get('/apprenants', [ApprenantFirebaseController::class, 'index']);
    Route::get('/apprenants/{id}', [ApprenantFirebaseController::class, 'show']);
    Route::put('/apprenants/{id}', [ApprenantFirebaseController::class, 'update']);
    Route::delete('/apprenants/{id}', [ApprenantFirebaseController::class, 'destroy']);
    Route::post('/apprenants/import', [ApprenantFirebaseController::class, 'import']);
    Route::get('/apprenants/inactive', [ApprenantFirebaseController::class, 'inactive']);
    Route::post('/apprenants/relance', [ApprenantFirebaseController::class, 'relanceMultiple']);
    Route::post('/apprenants/{id}/relance', [ApprenantFirebaseController::class, 'relance']);

});

// Routes publiques
/* Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login/{provider}', [AuthController::class, 'loginWithProvider']); */

// Routes
/* Route::middleware('auth:api')->group(function () {
    // User routes
    Route::apiResource('users', UserController::class);
    Route::get('users/filter/{role}', [UserController::class, 'getUsersByRole']);
    
    Route::post('logout', [AuthController::class, 'logout']);
    // Auth routes
    Route::get('user', [AuthController::class, 'user']);
}); */
/* Route::post('test', [UserController::class, 'store']); */