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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa yang mengirim
            $table->enum('category', ['akademik', 'fasilitas', 'bullying', 'lainnya'])->default('lainnya');
            $table->string('subject'); // Judul pengaduan
            $table->text('message'); // Isi pengaduan
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin/superadmin yang menyelesaikan
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};