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
        Schema::create('tugas_kelompok_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_id");
            $table->text("soal");
            $table->string("jawaban");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_kelompok_quizzes');
    }
};
