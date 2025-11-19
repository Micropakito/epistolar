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
        Schema::create('character_backgrounds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('character_id');
            $table->longText('public_background')->nullable();
            $table->longText('private_notes')->nullable();

            $table->unsignedBigInteger('last_updated_by')->nullable();

            $table->timestamps();

            $table->foreign('character_id')
                ->references('id')->on('characters')
                ->onDelete('cascade');

            $table->foreign('last_updated_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_backgrounds');
    }
};
