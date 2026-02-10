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
        Schema::create('ujian_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ujian_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'submitted', 'graded'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent')->nullable()->comment('Time spent in minutes');
            $table->integer('current_question')->default(1);
            $table->integer('total_questions')->default(0);
            $table->integer('answered_questions')->default(0);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'ujian_id']);
            $table->index(['status', 'ujian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_progress');
    }
};
