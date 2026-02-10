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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable(); // Quill editor content
            $table->enum('type', ['document', 'video', 'image', 'audio', 'text'])->default('text');
            $table->string('file_path')->nullable(); // Path untuk file upload
            $table->string('file_name')->nullable(); // Nama file asli
            $table->string('file_size')->nullable(); // Ukuran file
            $table->string('file_type')->nullable(); // MIME type
            $table->string('thumbnail_path')->nullable(); // Thumbnail untuk video/gambar
            $table->string('youtube_url')->nullable(); // URL YouTube
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('mapels')->onDelete('cascade');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
