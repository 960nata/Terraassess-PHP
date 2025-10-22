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
        // Ubah kolom feedback di tabel tugas_feedbacks
        Schema::table('tugas_feedbacks', function (Blueprint $table) {
            $table->longText('feedback')->change();
        });

        // Ubah kolom feedback di tabel tugas_mandiri_jawaban
        Schema::table('tugas_mandiri_jawaban', function (Blueprint $table) {
            $table->longText('feedback')->nullable()->change();
        });

        // Ubah kolom komentar di tabel tugas_kelompok_jawaban
        Schema::table('tugas_kelompok_jawaban', function (Blueprint $table) {
            $table->longText('komentar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke TEXT
        Schema::table('tugas_feedbacks', function (Blueprint $table) {
            $table->text('feedback')->change();
        });

        Schema::table('tugas_mandiri_jawaban', function (Blueprint $table) {
            $table->text('feedback')->nullable()->change();
        });

        Schema::table('tugas_kelompok_jawaban', function (Blueprint $table) {
            $table->text('komentar')->nullable()->change();
        });
    }
};
