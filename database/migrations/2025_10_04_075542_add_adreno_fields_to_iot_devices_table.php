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
        Schema::table('iot_devices', function (Blueprint $table) {
            $table->enum('connection_type', ['usb', 'ethernet', 'serial'])->default('usb')->after('device_type');
            $table->text('description')->nullable()->after('connection_type');
            $table->string('location')->nullable()->after('description');
            $table->unsignedBigInteger('class_id')->nullable()->after('location');
            $table->string('platform')->default('adreno')->after('class_id');
            $table->integer('data_points')->default(0)->after('platform');
            $table->timestamp('last_connected')->nullable()->after('last_seen');
            $table->timestamp('last_disconnected')->nullable()->after('last_connected');
            
            $table->foreign('class_id')->references('id')->on('kelas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iot_devices', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn([
                'connection_type',
                'description',
                'location',
                'class_id',
                'platform',
                'data_points',
                'last_connected',
                'last_disconnected'
            ]);
        });
    }
};
