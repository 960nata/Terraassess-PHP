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
            $table->foreignId("tugas_multiple_id")->nullable();
            $table->foreignId("tugas_quiz_id")->nullable();
            $table->foreignId("user_id");
            $table->string("user_jawaban");
            $table->bigInteger("nilai")->nullable();
            $table->text("koreksi")->nullable();
            $table->foreignId("tugas_kelompok_quiz_id")->nullable();
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
