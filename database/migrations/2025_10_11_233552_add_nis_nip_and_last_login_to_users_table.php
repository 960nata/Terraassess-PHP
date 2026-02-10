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
        Schema::table('users', function (Blueprint $table) {
            // Check if bio column exists before adding after it
            if (Schema::hasColumn('users', 'bio')) {
                $table->string('nis_nip')->nullable()->after('bio');
            } else {
                $table->string('nis_nip')->nullable();
            }
            $table->timestamp('last_login')->nullable()->after('nis_nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis_nip', 'last_login']);
        });
    }
};
