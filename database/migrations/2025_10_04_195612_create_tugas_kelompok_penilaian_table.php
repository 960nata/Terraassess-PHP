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
        Schema::create('tugas_kelompok_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->longText('deskripsi'); // Deskripsi pemilihan anggota dan ketua
            $table->foreignId('ketua_id')->constrained('users')->onDelete('cascade'); // Ketua kelompok
            
            // Penilaian Ya/Tidak
            $table->longText('pertanyaan_ya_tidak')->nullable();
            $table->integer('poin_ya')->default(100);
            $table->integer('poin_tidak')->default(50);
            
            // Penilaian Skala Setuju
            $table->longText('pertanyaan_skala')->nullable();
            $table->integer('poin_sangat_setuju')->default(100);
            $table->integer('poin_setuju')->default(75);
            $table->integer('poin_cukup_setuju')->default(50);
            $table->integer('poin_kurang_setuju')->default(25);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_kelompok_penilaian');
    }
};