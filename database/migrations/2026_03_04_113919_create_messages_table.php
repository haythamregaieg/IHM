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
       Schema::create('messages', function (Blueprint $table) {
    $table->id('idMessage');
    $table->foreignId('idEntreprise')
          ->constrained('entreprises','idEntreprise')
          ->cascadeOnDelete();
    $table->foreignId('idCandidat')
          ->constrained('candidats','idCandidat')
          ->cascadeOnDelete();
    $table->text('contenu');
    $table->date('dateEnvoi');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
