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
        Schema::table('kelas', function (Blueprint $table) {
            // Check if columns don't already exist before adding them
            if (!Schema::hasColumn('kelas', 'code')) {
                $table->string('code')->nullable()->after('name');
            }
            if (!Schema::hasColumn('kelas', 'major')) {
                $table->string('major')->nullable()->after('level');
            }
            if (!Schema::hasColumn('kelas', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('kelas', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('kelas', 'major')) {
                $table->dropColumn('major');
            }
            if (Schema::hasColumn('kelas', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
