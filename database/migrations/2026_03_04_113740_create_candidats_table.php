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
            $table->string('password');
            $table->unsignedBigInteger('idDomaine')->nullable();
            $table->string('specialite')->nullable();
            $table->string('niveau_etudes')->nullable();
            $table->string('niveau_experience')->nullable();
            $table->string('localisation')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();

            $table->foreign('idDomaine')->references('idDomaine')->on('domaines')->onDelete('set null');
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
