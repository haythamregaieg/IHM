<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'candidat'   => \App\Models\Candidat::class,
            'entreprise' => \App\Models\Entreprise::class,
            'offre'      => \App\Models\Offre::class,
            'message'    => \App\Models\Message::class,
        ]);
    }
}
