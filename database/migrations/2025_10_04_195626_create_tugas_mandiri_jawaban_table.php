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
        Schema::create('tugas_mandiri_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_mandiri_id')->constrained('tugas_mandiri')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->longText('jawaban'); // Jawaban siswa
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai yang diberikan guru
            $table->text('feedback')->nullable(); // Feedback dari guru
            $table->string('status')->default('submitted'); // submitted, graded
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi jawaban
            $table->unique(['tugas_mandiri_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_mandiri_jawaban');
    }
};