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
    Schema::create('story_participants', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('story_id');
        $table->unsignedBigInteger('user_id');

        $table->enum('role', ['GM', 'PLAYER'])->default('PLAYER');

        $table->enum('invitation_status', ['PENDING', 'ACCEPTED', 'DECLINED'])->default('ACCEPTED');
        $table->boolean('is_invited')->default(false);

        $table->timestamps();

        // FKs
        $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_participants');
    }
};
