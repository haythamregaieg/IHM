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
        Schema::table('candidatures', function (Blueprint $table) {
            if (!Schema::hasColumn('candidatures', 'cv_path')) {
                $table->string('cv_path')->nullable()->after('lettreMotivation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            if (Schema::hasColumn('candidatures', 'cv_path')) {
                $table->dropColumn('cv_path');
            }
        });
    }
};
