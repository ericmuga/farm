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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('farmer_name');
            $table->string('pf_no')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('id_no')->nullable();
            $table->string('id_photo_path')->nullable();
            $table->boolean('isActive');
            $table->string('status')->nullable();
            $table->json('created_geolocation')->nullable();
            $table->json('updated_geolocation')->nullable();
            $table->softDeletesTz();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
