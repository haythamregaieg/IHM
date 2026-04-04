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
        Schema::create('offres', function (Blueprint $table) {
    $table->id('idOffre');

    $table->foreignId('idEntreprise')
          ->constrained('entreprises','idEntreprise')
          ->cascadeOnDelete();

    $table->foreignId('idDomaine')
          ->constrained('domaines','idDomaine');


    $table->foreignId('idCategorie')
          ->constrained('categories','idCategorie');

    $table->foreignId('idRegion')
          ->constrained('regions','idRegion');

    $table->string('titre');
    $table->string('typeContrat');
    $table->text('description');
    $table->string('competencesRequises');
    $table->double('remuneration')->nullable();
    $table->date('datePublication')->nullable();
    $table->date('dateLimite');
    $table->string('statut');
    $table->string('localisation');
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
