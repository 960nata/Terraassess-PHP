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
        if (Schema::hasTable('user_ujians')) {
            Schema::table('user_ujians', function (Blueprint $table) {
                // Check if column doesn't already exist
                if (!Schema::hasColumn('user_ujians', 'feedback')) {
                    $table->text('feedback')->nullable()->after('nilai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ujians', function (Blueprint $table) {
            $table->dropColumn('feedback');
        });
    }
};
