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
        if (Schema::hasTable('tugas_multiples')) {
            Schema::table('tugas_multiples', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('tugas_multiples', 'poin')) {
                    $table->integer('poin')->default(1)->after('jawaban');
                }
                if (!Schema::hasColumn('tugas_multiples', 'kategori')) {
                    $table->string('kategori')->default('medium')->after('poin');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_multiples', function (Blueprint $table) {
            $table->dropColumn(['poin', 'kategori']);
        });
    }
};