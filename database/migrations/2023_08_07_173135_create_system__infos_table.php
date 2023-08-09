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
        Schema::create('system__infos', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('whatsapp_business_id');
            $table->string('phone_number_id');
            $table->string('phone_number');
            $table->string('facebook_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system__infos');
    }
};
