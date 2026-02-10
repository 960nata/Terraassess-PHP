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
        Schema::create('iot_sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('iot_devices')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Guru yang melakukan pengukuran
            $table->foreignId('research_project_id')->nullable()->constrained('research_projects')->onDelete('set null'); // Link to research project
            $table->decimal('soil_temperature', 5, 2); // Suhu tanah dalam Celsius
            $table->decimal('humidity', 5, 2); // Kelembaban dalam persen
            $table->decimal('soil_moisture', 5, 2); // Kelembaban tanah dalam persen
            $table->decimal('ph_level', 4, 2)->nullable(); // pH tanah
            $table->decimal('nitrogen', 5, 2)->nullable(); // Kandungan nitrogen
            $table->decimal('phosphorus', 5, 2)->nullable(); // Kandungan fosfor
            $table->decimal('potassium', 5, 2)->nullable(); // Kandungan kalium
            $table->string('thingsboard_device_token')->nullable(); // Token device ThingsBoard
            $table->string('location')->nullable(); // Lokasi pengukuran
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->json('raw_data')->nullable(); // Data mentah dari sensor
            $table->timestamp('measured_at'); // Waktu pengukuran
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['device_id', 'measured_at']);
            $table->index(['kelas_id', 'measured_at']);
            $table->index(['user_id', 'measured_at']);
            $table->index(['research_project_id', 'measured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_sensor_data');
    }
};
