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
        Schema::create('tugas_multiples', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_id");
            $table->text("soal");
            $table->text("a");
            $table->text("b");
            $table->text("c");
            $table->text("d")->nullable();
            $table->text("e")->nullable();
            $table->string("jawaban");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_multiples');
    }
};
