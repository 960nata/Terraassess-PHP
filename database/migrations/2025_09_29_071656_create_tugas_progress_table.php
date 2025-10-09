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
        Schema::create('tugas_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id');
            $table->foreignId('user_id');
            $table->string('status')->default('not_started'); // not_started, in_progress, submitted, graded
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->integer('final_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['tugas_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_progress');
    }
};