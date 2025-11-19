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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('story_id');

            $table->unsignedBigInteger('from_character_id');
            $table->unsignedBigInteger('from_user_id');

            $table->unsignedBigInteger('to_character_id');

            $table->longText('content_html');
            $table->longText('content_plain')->nullable();

            $table->enum('status', ['DRAFT', 'SENT'])->default('DRAFT');
            $table->boolean('is_story_advance')->default(false);

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('story_id')
                ->references('id')->on('stories')
                ->onDelete('cascade');

            $table->foreign('from_character_id')
                ->references('id')->on('characters')
                ->onDelete('cascade');

            $table->foreign('from_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('to_character_id')
                ->references('id')->on('characters')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
