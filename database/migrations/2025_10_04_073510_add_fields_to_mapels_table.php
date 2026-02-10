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
        if (Schema::hasTable('mapels')) {
            Schema::table('mapels', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('mapels', 'kategori')) {
                    $table->string('kategori')->default('akademik')->after('deskripsi');
                }
                if (!Schema::hasColumn('mapels', 'code')) {
                    $table->string('code')->nullable()->unique()->after('kategori');
                }
                if (!Schema::hasColumn('mapels', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('code');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'code', 'is_active']);
        });
    }
};
