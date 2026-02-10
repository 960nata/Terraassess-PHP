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
        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('kelas', 'level')) {
                    $table->string('level')->nullable()->after('name');
                }
                if (!Schema::hasColumn('kelas', 'description')) {
                    $table->text('description')->nullable()->after('level');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['level', 'description']);
        });
    }
};
