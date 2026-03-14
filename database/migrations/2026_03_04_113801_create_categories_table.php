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
<<<<<<< HEAD
       Schema::create('categories', function (Blueprint $table) {
=======
        Schema::create('categories', function (Blueprint $table) {
>>>>>>> c6b2ccc2324b85d4bd19dc69837f00c0feeb028f
    $table->id('idCategorie');
    $table->string('nom');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
