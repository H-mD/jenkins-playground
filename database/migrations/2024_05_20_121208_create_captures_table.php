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
        Schema::create('captures', function (Blueprint $table) {
            $table->id();
            $table->string('result');
            $table->string('image_url');
            $table->integer('rate')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->constrained();
            $table->foreignId('fish_id')->references('id')->on('fishes')->onDelete('cascade')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('captures');
    }
};
