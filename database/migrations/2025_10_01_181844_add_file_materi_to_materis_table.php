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
        if (Schema::hasTable('materis')) {
            Schema::table('materis', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('materis', 'file_materi')) {
                    $table->string('file_materi')->nullable()->after('content');
                }
                if (!Schema::hasColumn('materis', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('file_materi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn(['file_materi', 'deskripsi']);
        });
    }
};
