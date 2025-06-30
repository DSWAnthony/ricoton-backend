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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('location', 500)->nullable();
            $table->string('schedule',500)->nullable();
            $table->string('phone', 800)->nullable();
            $table->string('instagram', 800)->nullable();
            $table->string('facebook', 800)->nullable();
            $table->string('tiktok', 800)->nullable();
            $table->string('video_ref', 900)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
