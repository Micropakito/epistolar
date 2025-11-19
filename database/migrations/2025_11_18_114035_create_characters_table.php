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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('owner_user_id')->nullable(); // null → NPC del director

            $table->string('name');
            $table->enum('type', ['PC', 'NPC'])->default('PC');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // FKs
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
