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
        Schema::create('kelompok_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_kelompok_id'); // Kelompok yang dinilai
            $table->foreignId('penilai_kelompok_id'); // Kelompok yang menilai
            $table->foreignId('tugas_id');
            $table->integer('nilai_kerjasama')->nullable(); // 1-5
            $table->integer('nilai_kualitas')->nullable(); // 1-5
            $table->integer('nilai_presentasi')->nullable(); // 1-5
            $table->integer('nilai_inovasi')->nullable(); // 1-5
            $table->text('komentar')->nullable();
            $table->string('status')->default('pending'); // pending, completed
            $table->timestamps();
            
            $table->foreign('tugas_kelompok_id')->references('id')->on('tugas_kelompoks')->onDelete('cascade');
            $table->foreign('penilai_kelompok_id')->references('id')->on('tugas_kelompoks')->onDelete('cascade');
            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_penilaians');
    }
};