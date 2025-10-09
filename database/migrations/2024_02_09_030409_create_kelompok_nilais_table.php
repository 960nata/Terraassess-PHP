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
        Schema::create('kelompok_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_kelompok_id");
            $table->bigInteger("to_kelompok");
            $table->bigInteger("nilai")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_nilais');
    }
};
