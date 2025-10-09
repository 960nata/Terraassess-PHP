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
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('device_id')->unique(); // MAC address atau unique ID
            $table->string('bluetooth_address')->nullable();
            $table->string('device_type')->default('soil_sensor'); // soil_sensor, temperature, humidity, etc
            $table->string('status')->default('disconnected'); // connected, disconnected, error
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Owner of the device
            $table->json('device_info')->nullable(); // Additional device information
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_devices');
    }
};
