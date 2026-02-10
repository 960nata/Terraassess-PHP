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
        Schema::table('tugas_multiples', function (Blueprint $table) {
            // Ubah dari VARCHAR ke LONGTEXT untuk menampung gambar base64
            $table->longText('soal')->change();
            $table->longText('a')->change();
            $table->longText('b')->change();
            $table->longText('c')->change();
            $table->longText('d')->change();
            $table->longText('e')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_multiples', function (Blueprint $table) {
            // Rollback ke VARCHAR (255)
            $table->string('soal', 255)->change();
            $table->string('a', 255)->change();
            $table->string('b', 255)->change();
            $table->string('c', 255)->change();
            $table->string('d', 255)->change();
            $table->string('e', 255)->nullable()->change();
        });
    }
};
