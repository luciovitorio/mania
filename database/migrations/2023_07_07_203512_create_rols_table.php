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
        Schema::create('rols', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('clientId')->references('id')->on('clients');
            $table->foreignId('userId')->references('id')->on('users');
            $table->date('entryDate');
            $table->boolean('isHanger')->default(false);
            $table->enum('status', ['INICIO', 'ENVIADO', 'ACEITE', 'PRODUCAO', 'ENTREGA', 'FINALIZADA'])->default('INICIO');
            $table->date('deliveryDate')->nullable();
            $table->date('productionStartDate')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rols');
    }
};
