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
        Schema::table('mapels', function (Blueprint $table) {
            $table->string('kategori')->default('akademik')->after('deskripsi');
            $table->string('code')->nullable()->unique()->after('kategori');
            $table->boolean('is_active')->default(true)->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'code', 'is_active']);
        });
    }
};
