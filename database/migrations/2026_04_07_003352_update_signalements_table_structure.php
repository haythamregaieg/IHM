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
        Schema::table('signalements', function (Blueprint $table) {
            // Rendre idAdmin nullable car au début le signalement n'est pas traité par un admin
            $table->unsignedBigInteger('idAdmin')->nullable()->change();

            // Ajouter les colonnes polymorphes pour le signaleur (qui a fait le signalement)
            $table->string('signaleur_type')->after('idAdmin');
            $table->unsignedBigInteger('signaleur_id')->after('signaleur_type');

            // Ajouter les colonnes polymorphes pour la cible (ce qui est signalé)
            $table->string('cible_type')->after('signaleur_id');
            $table->unsignedBigInteger('cible_id')->after('cible_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            $table->unsignedBigInteger('idAdmin')->nullable(false)->change();
            $table->dropColumn(['signaleur_type', 'signaleur_id', 'cible_type', 'cible_id']);
        });
    }
};
