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
        Schema::create('tugas_jawaban_multiples', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_multiple_id")->nullable()->constrained('tugas_multiples')->onDelete('cascade');
            $table->foreignId("tugas_quiz_id")->nullable()->constrained('tugas_quizzes')->onDelete('cascade');
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->string("user_jawaban");
            $table->bigInteger("nilai")->nullable();
            $table->text("koreksi")->nullable();
            $table->foreignId("tugas_kelompok_quiz_id")->nullable()->constrained('tugas_kelompok_quizzes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_jawaban_multiples');
    }
};
