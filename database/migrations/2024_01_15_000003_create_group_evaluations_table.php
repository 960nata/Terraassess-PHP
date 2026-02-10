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
        Schema::create('group_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_task_id')->constrained('group_tasks')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // Ketua kelompok
            $table->foreignId('evaluated_id')->constrained('users')->onDelete('cascade'); // Yang dinilai
            $table->enum('rating', ['kurang_baik', 'cukup_baik', 'baik', 'sangat_baik']);
            $table->integer('points');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->unique(['group_task_id', 'evaluator_id', 'evaluated_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_evaluations');
    }
};
