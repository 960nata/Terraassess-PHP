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
        // Add indexes to tugas table
        Schema::table('tugas', function (Blueprint $table) {
            $table->index(['kelas_mapel_id', 'created_at']);
            $table->index(['tipe', 'isHidden']);
            $table->index(['due', 'isHidden']);
            $table->index('created_at');
        });

        // Add indexes to tugas_progress table
        Schema::table('tugas_progress', function (Blueprint $table) {
            $table->index(['tugas_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('status');
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['roles_id', 'kelas_id']);
            $table->index('roles_id');
            $table->index('kelas_id');
        });

        // Add indexes to kelas_mapels table if it exists
        if (Schema::hasTable('kelas_mapels')) {
            Schema::table('kelas_mapels', function (Blueprint $table) {
                $table->index(['kelas_id', 'mapel_id']);
                $table->index('kelas_id');
                $table->index('mapel_id');
            });
        }

        // Add indexes to editor_accesses table if it exists
        if (Schema::hasTable('editor_accesses')) {
            Schema::table('editor_accesses', function (Blueprint $table) {
                $table->index(['user_id', 'kelas_mapel_id']);
                $table->index('user_id');
                $table->index('kelas_mapel_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from tugas table
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex(['kelas_mapel_id', 'created_at']);
            $table->dropIndex(['tipe', 'isHidden']);
            $table->dropIndex(['due', 'isHidden']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from tugas_progress table
        Schema::table('tugas_progress', function (Blueprint $table) {
            $table->dropIndex(['tugas_id', 'status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status']);
        });

        // Remove indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['roles_id', 'kelas_id']);
            $table->dropIndex(['roles_id']);
            $table->dropIndex(['kelas_id']);
        });

        // Remove indexes from kelas_mapels table if it exists
        if (Schema::hasTable('kelas_mapels')) {
            Schema::table('kelas_mapels', function (Blueprint $table) {
                $table->dropIndex(['kelas_id', 'mapel_id']);
                $table->dropIndex(['kelas_id']);
                $table->dropIndex(['mapel_id']);
            });
        }

        // Remove indexes from editor_accesses table if it exists
        if (Schema::hasTable('editor_accesses')) {
            Schema::table('editor_accesses', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'kelas_mapel_id']);
                $table->dropIndex(['user_id']);
                $table->dropIndex(['kelas_mapel_id']);
            });
        }
    }
};