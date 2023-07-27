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
        Schema::create('clothins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branchId')->references('id')->on('branches');
            $table->string('name', 255);
            $table->enum('type', ['EASY', 'HARD'])->default('EASY');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothins');
    }
};
