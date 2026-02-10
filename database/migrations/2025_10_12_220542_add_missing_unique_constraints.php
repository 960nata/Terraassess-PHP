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
        // Add unique constraint to user_materis table if not exists
        if (Schema::hasTable('user_materis')) {
            Schema::table('user_materis', function (Blueprint $table) {
                // Check if unique constraint already exists
                if (!$this->constraintExists('user_materis', 'user_materis_user_id_materi_id_unique')) {
                    $table->unique(['user_id', 'materi_id'], 'user_materis_user_id_materi_id_unique');
                }
            });
        }

        // Add unique constraint to editor_accesses table if not exists
        if (Schema::hasTable('editor_accesses')) {
            Schema::table('editor_accesses', function (Blueprint $table) {
                // Check if unique constraint already exists
                if (!$this->constraintExists('editor_accesses', 'editor_accesses_user_id_kelas_mapel_id_unique')) {
                    $table->unique(['user_id', 'kelas_mapel_id'], 'editor_accesses_user_id_kelas_mapel_id_unique');
                }
            });
        }

        // Add unique constraint to kelas_mapels table if not exists
        if (Schema::hasTable('kelas_mapels')) {
            Schema::table('kelas_mapels', function (Blueprint $table) {
                // Check if unique constraint already exists
                if (!$this->constraintExists('kelas_mapels', 'kelas_mapels_kelas_id_mapel_id_unique')) {
                    $table->unique(['kelas_id', 'mapel_id'], 'kelas_mapels_kelas_id_mapel_id_unique');
                }
            });
        }

        // Add unique constraint to anggota_tugas_kelompoks table if not exists
        if (Schema::hasTable('anggota_tugas_kelompoks')) {
            Schema::table('anggota_tugas_kelompoks', function (Blueprint $table) {
                // Check if unique constraint already exists
                if (!$this->constraintExists('anggota_tugas_kelompoks', 'anggota_tugas_kelompoks_user_id_tugas_id_unique')) {
                    $table->unique(['user_id', 'tugas_id'], 'anggota_tugas_kelompoks_user_id_tugas_id_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Unique constraints that are part of foreign key relationships
        // cannot be dropped without first dropping the foreign keys.
        // This rollback is intentionally minimal to avoid breaking foreign key constraints.
        
        // Only drop unique constraints that are not part of foreign key relationships
        if (Schema::hasTable('kelas_mapels')) {
            try {
                Schema::table('kelas_mapels', function (Blueprint $table) {
                    $table->dropUnique('kelas_mapels_kelas_id_mapel_id_unique');
                });
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist or is part of foreign key
            }
        }
    }

    /**
     * Check if a constraint exists on a table
     */
    private function constraintExists(string $table, string $constraint): bool
    {
        try {
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND CONSTRAINT_NAME = ?
            ", [$table, $constraint]);
            
            return count($constraints) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
};