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
       Schema::create('offre_enregistres', function (Blueprint $table) {
    $table->id();
    $table->foreignId('idCandidat')
          ->constrained('candidats','idCandidat')
          ->cascadeOnDelete();
    $table->foreignId('idOffre')
          ->constrained('offres','idOffre')
          ->cascadeOnDelete();
    $table->date('dateAjout');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offre_enregistres');
    }
};
