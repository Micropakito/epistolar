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
    Schema::create('stories', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('creator_id'); // usuario que creó la historia
        $table->string('title');
        $table->text('description')->nullable();

        $table->enum('visibility', ['PUBLIC', 'PRIVATE', 'UNLISTED'])->default('PRIVATE');
        $table->enum('status', ['ACTIVE', 'PAUSED', 'FINISHED', 'ARCHIVED'])->default('ACTIVE');

        $table->text('rules')->nullable(); // notas del creador

        $table->timestamps();

        // FK
        $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
