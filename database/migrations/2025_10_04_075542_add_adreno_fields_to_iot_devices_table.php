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
        // Check if table exists before modifying
        if (Schema::hasTable('iot_devices')) {
            Schema::table('iot_devices', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('iot_devices', 'connection_type')) {
                    $table->enum('connection_type', ['usb', 'ethernet', 'serial'])->default('usb')->after('device_type');
                }
                if (!Schema::hasColumn('iot_devices', 'description')) {
                    $table->text('description')->nullable()->after('connection_type');
                }
                if (!Schema::hasColumn('iot_devices', 'location')) {
                    $table->string('location')->nullable()->after('description');
                }
                if (!Schema::hasColumn('iot_devices', 'class_id')) {
                    $table->unsignedBigInteger('class_id')->nullable()->after('location');
                }
                if (!Schema::hasColumn('iot_devices', 'platform')) {
                    $table->string('platform')->default('adreno')->after('class_id');
                }
                if (!Schema::hasColumn('iot_devices', 'data_points')) {
                    $table->integer('data_points')->default(0)->after('platform');
                }
                if (!Schema::hasColumn('iot_devices', 'last_connected')) {
                    $table->timestamp('last_connected')->nullable()->after('last_seen');
                }
                if (!Schema::hasColumn('iot_devices', 'last_disconnected')) {
                    $table->timestamp('last_disconnected')->nullable()->after('last_connected');
                }
                
                // Add foreign key if class_id column exists and foreign key doesn't exist
                if (Schema::hasColumn('iot_devices', 'class_id') && !Schema::hasColumn('iot_devices', 'class_id')) {
                    $table->foreign('class_id')->references('id')->on('kelas')->onDelete('set null');
                }
            });
        }
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
