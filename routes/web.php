<?php

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

    Route::resource('entreprises', EntrepriseController::class);
    Route::resource('domaines', DomaineController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('regions', RegionController::class);
    Route::resource('offres', OffreController::class);
    Route::resource('candidatures', CandidatureController::class);
    Route::resource('certificats', CertificatController::class);
    Route::resource('favoris', OffreEnregistreController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('signalements', SignalementController::class);

});
require __DIR__.'/auth.php';
