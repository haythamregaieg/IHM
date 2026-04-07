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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id('idEntreprise');
            $table->string('raison_sociale');
            $table->text('description')->nullable();
            $table->string('identifiant_legal')->unique();
            $table->unsignedBigInteger('idDomaine')->nullable();
            $table->unsignedBigInteger('idRegion')->nullable();
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('site_web')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('statut')->default('en_attente');
            $table->timestamps();

            $table->foreign('idDomaine')->references('idDomaine')->on('domaines')->onDelete('set null');
            $table->foreign('idRegion')->references('idRegion')->on('regions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
