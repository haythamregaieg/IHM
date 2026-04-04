<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\CandidatsController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CertificatController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\OffreEnregistreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SignalementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function(){
    Route::apiResource('entreprises', EntrepriseController::class);
    Route::apiResource('domaines', DomaineController::class);
    Route::apiResource('categories', CategorieController::class);
    Route::apiResource('regions', RegionController::class);
    Route::apiResource('offres', OffreController::class);
    Route::apiResource('candidatures', CandidatureController::class);
    Route::apiResource('certificats', CertificatController::class);
    Route::apiResource('favoris', OffreEnregistreController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('signalements', SignalementController::class);
    Route::apiResource('admins', AdminsController::class);
    Route::apiResource('candidats', CandidatsController::class);
});

require __DIR__.'/auth.php';
