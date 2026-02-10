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
        if (Schema::hasTable('tugas_quizzes')) {
            Schema::table('tugas_quizzes', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('tugas_quizzes', 'poin')) {
                    $table->integer('poin')->default(10)->after('soal');
                }
                if (!Schema::hasColumn('tugas_quizzes', 'kategori')) {
                    $table->string('kategori')->default('medium')->after('poin'); // easy, medium, hard
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_quizzes', function (Blueprint $table) {
            $table->dropColumn(['poin', 'kategori']);
        });
    }
};
