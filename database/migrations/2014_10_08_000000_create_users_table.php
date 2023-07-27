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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branchId')->references('id')->on('branches');
            $table->string('name', 255);
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cpf', 16);
            $table->date('dateOfBirth')->nullable();
            $table->enum('profile', ['ADMIN', 'SUPERVISAO', 'PASSADEIRA'])->default('PASSADEIRA');
            $table->integer('percent')->nullable();
            $table->boolean('isActive')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
