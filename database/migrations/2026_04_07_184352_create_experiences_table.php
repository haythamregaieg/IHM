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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCandidat');
            $table->string('poste');
            $table->string('entreprise');
            $table->date('dateDebut');
            $table->date('dateFin')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('idCandidat')->references('idCandidat')->on('candidats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
