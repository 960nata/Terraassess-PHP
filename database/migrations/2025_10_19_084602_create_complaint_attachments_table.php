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
        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->onDelete('cascade');
            $table->foreignId('complaint_reply_id')->nullable()->constrained('complaint_replies')->onDelete('cascade');
            $table->string('file_name'); // Nama file asli
            $table->string('file_path'); // Path file di storage
            $table->string('file_type'); // MIME type (image/jpeg, application/pdf, etc.)
            $table->string('file_extension'); // Extension file (.jpg, .pdf, etc.)
            $table->bigInteger('file_size'); // Ukuran file dalam bytes
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['complaint_id', 'created_at']);
            $table->index(['complaint_reply_id', 'created_at']);
            $table->index(['uploaded_by', 'created_at']);
            
            // Note: Either complaint_id or complaint_reply_id should be set, but not both
            // This constraint will be enforced at the application level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_attachments');
    }
};