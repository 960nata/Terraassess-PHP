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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->index(); // UUID device ESP8266
            $table->decimal('temperature', 5, 2)->nullable(); // Suhu dalam °C
            $table->decimal('humidity', 5, 2)->nullable(); // Kelembaban dalam %
            $table->decimal('ph', 4, 2)->nullable(); // Tingkat pH
            $table->decimal('conductivity', 8, 2)->nullable(); // Konduktivitas dalam µS/cm
            $table->integer('nitrogen')->nullable(); // Nitrogen dalam mg/kg
            $table->integer('phosphorus')->nullable(); // Fosfor dalam mg/kg
            $table->integer('potassium')->nullable(); // Kalium dalam mg/kg
            $table->timestamp('recorded_at')->useCurrent(); // Waktu pencatatan
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['device_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};