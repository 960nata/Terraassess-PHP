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
        // Check if table exists before modifying
        if (Schema::hasTable('tugas_kelompoks')) {
            Schema::table('tugas_kelompoks', function (Blueprint $table) {
                // Add kelas_id to link group to specific class
                if (!Schema::hasColumn('tugas_kelompoks', 'kelas_id')) {
                    $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade')->after('tugas_id');
                }
                
                // Add is_template to mark reusable groups
                if (!Schema::hasColumn('tugas_kelompoks', 'is_template')) {
                    $table->boolean('is_template')->default(false)->after('status');
                }
                
                // Add created_by to track who created the group
                if (!Schema::hasColumn('tugas_kelompoks', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('is_template');
                }
                
                // Add description field for group details
                if (!Schema::hasColumn('tugas_kelompoks', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('tugas_kelompoks')) {
            Schema::table('tugas_kelompoks', function (Blueprint $table) {
                // Drop foreign keys first
                if (Schema::hasColumn('tugas_kelompoks', 'kelas_id')) {
                    $table->dropForeign(['kelas_id']);
                }
                if (Schema::hasColumn('tugas_kelompoks', 'created_by')) {
                    $table->dropForeign(['created_by']);
                }
                
                // Drop columns
                $table->dropColumn(['kelas_id', 'is_template', 'created_by', 'description']);
            });
        }
    }
};