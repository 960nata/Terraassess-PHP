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
        Schema::create('tugas_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id');
            $table->foreignId('user_id'); // Siswa yang diberi feedback
            $table->foreignId('guru_id'); // Guru yang memberikan feedback
            $table->text('feedback');
            $table->integer('rating')->nullable(); // 1-5 rating
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
            
            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_feedbacks');
    }
};