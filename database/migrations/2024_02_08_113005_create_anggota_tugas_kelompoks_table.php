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
        Schema::create('anggota_tugas_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tugas_kelompok_id")->constrained('tugas_kelompoks')->onDelete('cascade');
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->foreignId("tugas_id")->constrained('tugas')->onDelete('cascade');
            $table->bigInteger("isKetua")->default(0);
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate user-tugas membership
            $table->unique(['user_id', 'tugas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_tugas_kelompoks');
    }
};
