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
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'expediteur')) {
                $table->string('expediteur')->default('candidat'); // 'candidat' ou 'entreprise'
            }
            if (!Schema::hasColumn('messages', 'lu')) {
                $table->boolean('lu')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['expediteur', 'lu']);
        });
    }
};
