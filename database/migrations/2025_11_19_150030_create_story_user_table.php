<?php

// database/migrations/2025_11_19_000000_create_story_user_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('story_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Opcional: rol dentro de la historia
            $table->string('role')->default('participant'); 
            // ejemplo: owner, recipient, cc, etc.

            $table->timestamps();

            $table->unique(['story_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_user');
    }
};
