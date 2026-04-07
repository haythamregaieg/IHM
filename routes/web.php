<?php
use Illuminate\Support\Facades\Route;
Route::get('/',                        fn()=>view('pages.home'));
Route::get('/offres',                  fn()=>view('offres.index'));
Route::get('/offres/{id}',             fn()=>view('offres.show'));
Route::get('/connexion',               fn()=>view('auth.login'));
Route::get('/inscription/candidat',    fn()=>view('auth.register-candidat'));
Route::get('/inscription/entreprise',  fn()=>view('auth.register-entreprise'));
Route::get('/mon-espace/{tab?}',       fn()=>view('candidat.dashboard'));
Route::get('/recruteur/{tab?}',        fn()=>view('entreprise.dashboard'));
Route::get('/admin/{tab?}',            fn()=>view('admin.dashboard'));
Route::get('/{any}', fn()=>view('pages.home'))->where('any','^(?!api).*$');
