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
    $table->string('raisonSociale');
    $table->text('description')->nullable();
    $table->string('identifiantLegal')->unique();
    $table->string('domaine');
    $table->string('pays');
    $table->string('ville');
    $table->string('telephone');
    $table->string('logo')->nullable();
    $table->string('siteWeb')->nullable();
    $table->string('email')->unique();
    $table->string('motDePasse');
    $table->string('statut')->default('en_attente');
    $table->timestamps();
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
