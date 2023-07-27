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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planId')->references('id')->on('plans');
            $table->foreignId('branchId')->references('id')->on('branches');
            $table->string('name', 255);
            $table->string('cpf', 16)->nullable();
            $table->string('rg', 20)->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('email')->nullable();
            $table->string('homePhone', 20)->nullable();
            $table->string('cellPhone', 20)->nullable();
            $table->enum('collectionFrequency', ['SEMANAL', 'QUINZENAL', 'MENSAL', 'AVULSO'])->default('AVULSO');
            $table->enum('collectionDay', ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO', 'DOMINGO'])->default('SEGUNDA')->nullable();
            $table->time('collectionTime')->default('00:00:00')->nullable();
            $table->enum('deliveryDay', ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO', 'DOMINGO'])->default('SEGUNDA')->nullable();
            $table->time('deliveryTime')->default('00:00:00')->nullable();
            $table->date('collectionStart')->nullable();
            $table->text('description')->nullable();
            $table->boolean('deliveryFee')->default(false);
            $table->decimal('deliveryAmount', 20, 2)->nullable();
            $table->integer('dueDate')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
