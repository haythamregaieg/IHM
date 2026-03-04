<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('candidats', function (Blueprint $table) {
    $table->id('idCandidat');
    $table->string('nom');
    $table->string('prenom');
    $table->string('email')->unique();
    $table->string('motDePasse');
    $table->string('domaine');
    $table->string('specialite');
    $table->string('niveauEtude')->nullable();
    $table->string('niveauExperience')->nullable();
    $table->string('localisation')->nullable();
    $table->string('photo')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
