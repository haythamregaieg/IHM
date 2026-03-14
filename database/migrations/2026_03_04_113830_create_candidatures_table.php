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
       Schema::create('candidatures', function (Blueprint $table) {
    $table->id('idCandidature');

    $table->foreignId('idCandidat')
          ->constrained('candidats','idCandidat')
          ->cascadeOnDelete();

    $table->foreignId('idOffre')
          ->constrained('offres','idOffre')
          ->cascadeOnDelete();

    $table->date('dateCandidature');
    $table->string('statut');
    $table->text('lettreMotivation');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
