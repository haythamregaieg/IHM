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
    Schema::create('signalements', function (Blueprint $table) {
    $table->id('idSignalement');
    $table->foreignId('idAdmin')
          ->constrained('admins','idAdmin')
          ->cascadeOnDelete();
    $table->string('description');
    $table->date('date');
    $table->string('statut');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
