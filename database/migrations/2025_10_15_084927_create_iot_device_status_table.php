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
        Schema::create('iot_device_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('iot_devices')->onDelete('cascade');
            $table->boolean('is_online')->default(false);
            $table->string('wifi_ssid')->nullable();
            $table->integer('wifi_rssi')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->json('system_info')->nullable();
            $table->timestamps();
            
            $table->index('device_id');
            $table->index('is_online');
            $table->index('last_seen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_device_status');
    }
};