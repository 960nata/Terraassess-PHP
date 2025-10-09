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
        Schema::create('iot_readings', function (Blueprint $table) {
            $table->id();
            $table->string('student_id'); // NIS atau ID siswa
            $table->string('class_id'); // ID kelas
            $table->timestamp('timestamp')->default(now());
            $table->decimal('soil_temperature', 5, 2)->nullable(); // Suhu tanah dalam Celsius
            $table->decimal('soil_humus', 5, 2)->nullable(); // Kadar humus dalam persen
            $table->decimal('soil_moisture', 5, 2)->nullable(); // Kelembaban tanah dalam persen
            $table->string('device_id')->nullable(); // ID perangkat IoT
            $table->enum('created_by_role', ['student', 'teacher']); // Role yang membuat data
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // User yang membuat data
            $table->string('location')->nullable(); // Lokasi pengukuran
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->json('raw_data')->nullable(); // Data mentah dari sensor
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['student_id', 'timestamp']);
            $table->index(['class_id', 'timestamp']);
            $table->index(['device_id', 'timestamp']);
            $table->index(['created_by_role', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_readings');
    }
};