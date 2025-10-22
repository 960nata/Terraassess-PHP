<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('iot_sensor_data', function (Blueprint $table) {
            // Add research_project_id if not exists
            if (!Schema::hasColumn('iot_sensor_data', 'research_project_id')) {
                $table->foreignId('research_project_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('research_projects')
                    ->onDelete('set null');
                
                // Add index
                $table->index(['research_project_id', 'measured_at']);
            }
            
            // Add nitrogen, phosphorus, potassium if not exists
            if (!Schema::hasColumn('iot_sensor_data', 'nitrogen')) {
                $table->decimal('nitrogen', 5, 2)->nullable()->after('ph_level');
            }
            if (!Schema::hasColumn('iot_sensor_data', 'phosphorus')) {
                $table->decimal('phosphorus', 5, 2)->nullable()->after('nitrogen');
            }
            if (!Schema::hasColumn('iot_sensor_data', 'potassium')) {
                $table->decimal('potassium', 5, 2)->nullable()->after('phosphorus');
            }
            
            // Add thingsboard_device_token if not exists
            if (!Schema::hasColumn('iot_sensor_data', 'thingsboard_device_token')) {
                $table->string('thingsboard_device_token')->nullable()->after('potassium');
            }
        });
        
        // Rename temperature to soil_temperature if needed
        if (Schema::hasColumn('iot_sensor_data', 'temperature') && 
            !Schema::hasColumn('iot_sensor_data', 'soil_temperature')) {
            DB::statement('ALTER TABLE iot_sensor_data CHANGE temperature soil_temperature DECIMAL(5,2)');
        }
        
        // Drop nutrient_level if exists (replaced by nitrogen, phosphorus, potassium)
        if (Schema::hasColumn('iot_sensor_data', 'nutrient_level')) {
            Schema::table('iot_sensor_data', function (Blueprint $table) {
                $table->dropColumn('nutrient_level');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iot_sensor_data', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('iot_sensor_data', 'research_project_id')) {
                $table->dropForeign(['research_project_id']);
                $table->dropColumn('research_project_id');
            }
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
        });
        
        // Rename soil_temperature back to temperature if needed
        if (Schema::hasColumn('iot_sensor_data', 'soil_temperature') && 
            !Schema::hasColumn('iot_sensor_data', 'temperature')) {
            DB::statement('ALTER TABLE iot_sensor_data CHANGE soil_temperature temperature DECIMAL(5,2)');
        }
        
        // Add back nutrient_level if it doesn't exist
        if (!Schema::hasColumn('iot_sensor_data', 'nutrient_level')) {
            Schema::table('iot_sensor_data', function (Blueprint $table) {
                $table->decimal('nutrient_level', 5, 2)->nullable()->after('ph_level');
            });
        }
    }
};
