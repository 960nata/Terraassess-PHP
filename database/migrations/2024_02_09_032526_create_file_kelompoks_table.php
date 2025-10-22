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
        Schema::create('file_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_kelompok_id")->constrained('tugas_kelompoks')->onDelete('cascade');
            $table->text("file");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_kelompoks');
    }
};
