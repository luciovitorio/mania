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
        Schema::create('rol_clothins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rolId')->references('id')->on('rols');
            $table->foreignId('clothinId')->references('id')->on('clothins');
            $table->integer('clothQuantity');
            $table->integer('hangQuantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_clothins');
    }
};
