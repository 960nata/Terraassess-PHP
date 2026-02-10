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
        Schema::create('tugas_kelompok_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('kelompok_id')->constrained('tugas_kelompoks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Yang mengisi jawaban
            
            // Jawaban Ya/Tidak
            $table->enum('jawaban_ya_tidak', ['ya', 'tidak'])->nullable();
            $table->integer('nilai_ya_tidak')->nullable();
            
            // Jawaban Skala Setuju
            $table->enum('jawaban_skala', ['sangat_setuju', 'setuju', 'cukup_setuju', 'kurang_setuju'])->nullable();
            $table->integer('nilai_skala')->nullable();
            
            $table->text('komentar')->nullable(); // Komentar tambahan
            $table->string('status')->default('submitted'); // submitted, graded
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi jawaban per kelompok
            $table->unique(['tugas_id', 'kelompok_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_kelompok_jawaban');
    }
};