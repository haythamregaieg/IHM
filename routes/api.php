<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Auth\AuthCandidatController;
use App\Http\Controllers\Auth\AuthEntrepriseController;

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\CandidatsController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CertificatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\OffreEnregistreController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SignalementController;

// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// ROUTES PUBLIQUES
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

// Auth - Admin
Route::post('/admin/register', [AuthAdminController::class, 'register']);
Route::post('/admin/login',    [AuthAdminController::class, 'login']);

// Auth - Candidat
Route::post('/candidat/register', [AuthCandidatController::class, 'register']);
Route::post('/candidat/login',    [AuthCandidatController::class, 'login']);

// Auth - Entreprise
Route::post('/entreprise/register', [AuthEntrepriseController::class, 'register']);
Route::post('/entreprise/login',    [AuthEntrepriseController::class, 'login']);

// Donnees de reference (publiques)
Route::get('/domaines',   [DomaineController::class, 'index']);
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/regions',    [RegionController::class, 'index']);

// Offres (recherche publique)
Route::get('/offres',        [OffreController::class, 'index']);
Route::get('/offres/{id}',   [OffreController::class, 'show']);

// Entreprises publiques (validees uniquement)
Route::get('/entreprises',       [EntrepriseController::class, 'index']);
Route::get('/entreprises/{id}',  [EntrepriseController::class, 'show']);

// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// ROUTES ADMIN
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::post('/logout', [AuthAdminController::class, 'logout']);
    Route::get('/me',      [AuthAdminController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminStats']);

    // Gestion candidats
    Route::get('/candidats',       [CandidatsController::class, 'index']);
    Route::get('/candidats/{id}',  [CandidatsController::class, 'show']);
    Route::delete('/candidats/{id}', [CandidatsController::class, 'destroy']);

    // Gestion entreprises
    Route::get('/entreprises',                          [EntrepriseController::class, 'adminIndex']);
    Route::get('/entreprises/{id}',                     [EntrepriseController::class, 'adminShow']);
    Route::patch('/entreprises/{id}/statut',            [EntrepriseController::class, 'updateStatut']);
    Route::delete('/entreprises/{id}',                  [EntrepriseController::class, 'destroy']);

    // Gestion offres
    Route::get('/offres',                               [OffreController::class, 'adminIndex']);
    Route::patch('/offres/{id}/statut',                 [OffreController::class, 'adminUpdateStatut']);

    // Gestion candidatures
    Route::get('/candidatures', [CandidatureController::class, 'adminIndex']);

    // Signalements
    Route::get('/signalements',          [SignalementController::class, 'index']);
    Route::get('/signalements/{id}',     [SignalementController::class, 'show']);
    Route::patch('/signalements/{id}',   [SignalementController::class, 'update']);
    Route::delete('/signalements/{id}',  [SignalementController::class, 'destroy']);

    // Donnees de reference (CRUD complet)
    Route::apiResource('/domaines',   DomaineController::class);
    Route::apiResource('/categories', CategorieController::class);
    Route::apiResource('/regions',    RegionController::class);

    // Gestion admins
    Route::apiResource('/admins', AdminsController::class);
});

// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// ROUTES CANDIDAT
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware('candidat')->prefix('candidat')->group(function () {
    Route::post('/logout',           [AuthCandidatController::class, 'logout']);
    Route::get('/me',                [AuthCandidatController::class, 'me']);
    Route::post('/profile',          [AuthCandidatController::class, 'updateProfile']);
    Route::post('/importer-cv',      [AuthCandidatController::class, 'importCV']);
    Route::put('/password',          [AuthCandidatController::class, 'updatePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'candidatStats']);

    // Candidatures
    Route::get('/candidatures',               [CandidatureController::class, 'mesCandidatures']);
    Route::post('/candidatures',              [CandidatureController::class, 'store']);
    Route::delete('/candidatures/{id}',       [CandidatureController::class, 'destroy']);

    // Favoris
    Route::get('/favoris',                    [OffreEnregistreController::class, 'index']);
    Route::post('/favoris',                   [OffreEnregistreController::class, 'store']);
    Route::delete('/favoris/{id}',            [OffreEnregistreController::class, 'destroy']);
    Route::post('/favoris/toggle/{idOffre}',  [OffreEnregistreController::class, 'toggle']);

    // Certificats
    Route::get('/certificats',                       [\App\Http\Controllers\CertificatController::class, 'index']);
    Route::post('/certificats',                      [\App\Http\Controllers\CertificatController::class, 'store']);
    Route::put('/certificats/{id}',                  [\App\Http\Controllers\CertificatController::class, 'update']);
    Route::delete('/certificats/{id}',               [\App\Http\Controllers\CertificatController::class, 'destroy']);

    // Expériences
    Route::get('/experiences',                       [\App\Http\Controllers\ExperienceController::class, 'index']);
    Route::post('/experiences',                      [\App\Http\Controllers\ExperienceController::class, 'store']);
    Route::put('/experiences/{id}',                  [\App\Http\Controllers\ExperienceController::class, 'update']);
    Route::delete('/experiences/{id}',               [\App\Http\Controllers\ExperienceController::class, 'destroy']);

    // Messages
    Route::get('/messages/unread-count',              [MessageController::class, 'unreadCount']);
    Route::get('/messages',                           [MessageController::class, 'mesConversations']);
    Route::get('/messages/{idEntreprise}',            [MessageController::class, 'conversation']);
    Route::post('/messages',                          [MessageController::class, 'store']);
    Route::delete('/messages/{id}',                   [MessageController::class, 'destroy']);

    // Signalements
    Route::post('/signalements', [SignalementController::class, 'store']);
});

// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// ROUTES ENTREPRISE
// â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware('entreprise')->prefix('entreprise')->group(function () {
    Route::post('/logout',      [AuthEntrepriseController::class, 'logout']);
    Route::get('/me',           [AuthEntrepriseController::class, 'me']);
    Route::put('/profile',      [AuthEntrepriseController::class, 'updateProfile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'entrepriseStats']);

    // Offres
    Route::get('/offres',           [OffreController::class, 'mesOffres']);
    Route::post('/offres',          [OffreController::class, 'store']);
    Route::put('/offres/{id}',      [OffreController::class, 'update']);
    Route::patch('/offres/{id}/suspendre', [OffreController::class, 'suspendre']);
    Route::delete('/offres/{id}',   [OffreController::class, 'destroy']);

    // Candidatures recues
    Route::get('/candidatures',                       [CandidatureController::class, 'toutesParEntreprise']);
    Route::get('/offres/{idOffre}/candidatures',      [CandidatureController::class, 'parOffre']);
    Route::patch('/candidatures/{id}/statut',         [CandidatureController::class, 'updateStatut']);

    // Messages
    Route::get('/messages/unread-count',         [MessageController::class, 'unreadCount']);
    Route::get('/messages',                      [MessageController::class, 'mesConversations']);
    Route::get('/messages/{idCandidat}',         [MessageController::class, 'conversation']);
    Route::post('/messages',                     [MessageController::class, 'store']);
    Route::delete('/messages/{id}',              [MessageController::class, 'destroy']);

    // Signalements
    Route::post('/signalements', [SignalementController::class, 'store']);
});
