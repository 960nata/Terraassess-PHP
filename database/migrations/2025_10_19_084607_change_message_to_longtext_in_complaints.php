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
        // Change message column in complaints table to LONGTEXT
        Schema::table('complaints', function (Blueprint $table) {
            $table->longText('message')->change();
        });
        
        // Change message column in complaint_replies table to LONGTEXT
        Schema::table('complaint_replies', function (Blueprint $table) {
            $table->longText('message')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to TEXT
        Schema::table('complaints', function (Blueprint $table) {
            $table->text('message')->change();
        });
        
        Schema::table('complaint_replies', function (Blueprint $table) {
            $table->text('message')->change();
        });
    }
};