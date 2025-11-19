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
    Schema::table('friendships', function (Blueprint $table) {
        // solo añadir si NO existen todavía (por seguridad)
        if (!Schema::hasColumn('friendships', 'requester_id')) {
            $table->unsignedBigInteger('requester_id')->after('id');
        }
        if (!Schema::hasColumn('friendships', 'addressee_id')) {
            $table->unsignedBigInteger('addressee_id')->after('requester_id');
        }
        if (!Schema::hasColumn('friendships', 'status')) {
            $table->enum('status', ['PENDING', 'ACCEPTED', 'DECLINED', 'BLOCKED'])
                  ->default('PENDING')
                  ->after('addressee_id');
        }
        if (!Schema::hasColumn('friendships', 'responded_at')) {
            $table->timestamp('responded_at')->nullable()->after('status');
        }

        // FKs
        $table->foreign('requester_id')
              ->references('id')->on('users')
              ->onDelete('cascade');

        $table->foreign('addressee_id')
              ->references('id')->on('users')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('friendships', function (Blueprint $table) {
        if (Schema::hasColumn('friendships', 'requester_id')) {
            $table->dropForeign(['requester_id']);
            $table->dropColumn('requester_id');
        }
        if (Schema::hasColumn('friendships', 'addressee_id')) {
            $table->dropForeign(['addressee_id']);
            $table->dropColumn('addressee_id');
        }
        if (Schema::hasColumn('friendships', 'status')) {
            $table->dropColumn('status');
        }
        if (Schema::hasColumn('friendships', 'responded_at')) {
            $table->dropColumn('responded_at');
        }
    });
}

};
