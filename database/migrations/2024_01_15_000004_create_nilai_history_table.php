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
        Schema::create('nilai_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_tugas_id')->constrained('user_tugas')->onDelete('cascade');
            $table->integer('nilai_lama');
            $table->integer('nilai_baru');
            $table->text('komentar_lama')->nullable();
            $table->text('komentar_baru')->nullable();
            $table->foreignId('diubah_oleh')->constrained('users');
            $table->text('alasan_revisi')->nullable();
            $table->timestamp('diubah_pada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_history');
    }
};
