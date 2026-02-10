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
        if (Schema::hasTable('iot_sensor_data')) {
            Schema::table('iot_sensor_data', function (Blueprint $table) {
                // Add new nutrient columns first (check if they don't exist)
                if (!Schema::hasColumn('iot_sensor_data', 'nitrogen')) {
                    $table->decimal('nitrogen', 5, 2)->nullable()->after('ph_level');
                }
                if (!Schema::hasColumn('iot_sensor_data', 'phosphorus')) {
                    $table->decimal('phosphorus', 5, 2)->nullable()->after('nitrogen');
                }
                if (!Schema::hasColumn('iot_sensor_data', 'potassium')) {
                    $table->decimal('potassium', 5, 2)->nullable()->after('phosphorus');
                }
                
                // Add ThingsBoard device token
                if (!Schema::hasColumn('iot_sensor_data', 'thingsboard_device_token')) {
                    $table->string('thingsboard_device_token')->nullable()->after('potassium');
                }
            });
            
            // Use raw SQL for column rename (MariaDB compatibility) - only if temperature column exists
            if (Schema::hasColumn('iot_sensor_data', 'temperature')) {
                DB::statement('ALTER TABLE iot_sensor_data CHANGE temperature soil_temperature DECIMAL(5,2)');
            }
            
            // Remove old nutrient_level column if exists
            if (Schema::hasColumn('iot_sensor_data', 'nutrient_level')) {
                Schema::table('iot_sensor_data', function (Blueprint $table) {
                    $table->dropColumn('nutrient_level');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('iot_sensor_data')) {
            // Use raw SQL for column rename (MariaDB compatibility)
            if (Schema::hasColumn('iot_sensor_data', 'soil_temperature')) {
                DB::statement('ALTER TABLE iot_sensor_data CHANGE soil_temperature temperature DECIMAL(5,2)');
            }
            
            Schema::table('iot_sensor_data', function (Blueprint $table) {
                // Drop new columns if they exist
                if (Schema::hasColumn('iot_sensor_data', 'nitrogen')) {
                    $table->dropColumn('nitrogen');
                }
                if (Schema::hasColumn('iot_sensor_data', 'phosphorus')) {
                    $table->dropColumn('phosphorus');
                }
                if (Schema::hasColumn('iot_sensor_data', 'potassium')) {
                    $table->dropColumn('potassium');
                }
                if (Schema::hasColumn('iot_sensor_data', 'thingsboard_device_token')) {
                    $table->dropColumn('thingsboard_device_token');
                }
                
                // Add back old column if it doesn't exist
                if (!Schema::hasColumn('iot_sensor_data', 'nutrient_level')) {
                    $table->string('nutrient_level')->nullable();
                }
            });
        }
    }
};
