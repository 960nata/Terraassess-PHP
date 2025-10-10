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
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->string('aspek'); // Isi, Struktur, Tata Bahasa, dll
            $table->integer('bobot'); // Persentase (total 100)
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('user_tugas_rubrik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_tugas_id')->constrained('user_tugas')->onDelete('cascade');
            $table->foreignId('rubrik_id')->constrained('rubrik_penilaian')->onDelete('cascade');
            $table->integer('nilai'); // Nilai untuk aspek ini
            $table->text('komentar_aspek')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tugas_rubrik');
        Schema::dropIfExists('rubrik_penilaian');
    }
};
