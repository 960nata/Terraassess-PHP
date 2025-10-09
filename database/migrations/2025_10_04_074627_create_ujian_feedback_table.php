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
        Schema::create('ujian_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->text('feedback_text')->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('suggestions')->nullable();
            $table->integer('rating')->nullable()->comment('Rating 1-5 stars');
            $table->enum('status', ['pending', 'graded', 'reviewed'])->default('pending');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            
            $table->unique(['ujian_id', 'user_id']);
            $table->index(['teacher_id', 'status']);
            $table->index(['ujian_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_feedback');
    }
};
