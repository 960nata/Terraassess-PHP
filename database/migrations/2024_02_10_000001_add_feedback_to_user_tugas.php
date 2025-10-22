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
        // Tambah kolom feedback ke tabel user_tugas (dengan pengecekan)
        Schema::table('user_tugas', function (Blueprint $table) {
            if (!Schema::hasColumn('user_tugas', 'komentar')) {
                $table->text('komentar')->nullable()->after('nilai');
            }
            if (!Schema::hasColumn('user_tugas', 'dinilai_oleh')) {
                $table->unsignedBigInteger('dinilai_oleh')->nullable()->after('komentar');
            }
            if (!Schema::hasColumn('user_tugas', 'dinilai_pada')) {
                $table->timestamp('dinilai_pada')->nullable()->after('dinilai_oleh');
            }
            if (!Schema::hasColumn('user_tugas', 'revisi_ke')) {
                $table->integer('revisi_ke')->default(0)->after('dinilai_pada');
            }
        });

        // Tambah foreign key jika belum ada
        if (Schema::hasColumn('user_tugas', 'dinilai_oleh')) {
            try {
                Schema::table('user_tugas', function (Blueprint $table) {
                    $table->foreign('dinilai_oleh')->references('id')->on('users')->onDelete('set null');
                });
            } catch (Exception $e) {
                // Foreign key sudah ada, skip
            }
        }

        // Tambah kolom feedback ke tabel kelompok_nilais (dengan pengecekan)
        if (Schema::hasTable('kelompok_nilais')) {
            Schema::table('kelompok_nilais', function (Blueprint $table) {
                if (!Schema::hasColumn('kelompok_nilais', 'komentar')) {
                    $table->text('komentar')->nullable()->after('nilai');
                }
                if (!Schema::hasColumn('kelompok_nilais', 'dinilai_oleh')) {
                    $table->unsignedBigInteger('dinilai_oleh')->nullable()->after('komentar');
                }
                if (!Schema::hasColumn('kelompok_nilais', 'dinilai_pada')) {
                    $table->timestamp('dinilai_pada')->nullable()->after('dinilai_oleh');
                }
            });

            // Tambah foreign key jika belum ada
            if (Schema::hasColumn('kelompok_nilais', 'dinilai_oleh')) {
                try {
                    Schema::table('kelompok_nilais', function (Blueprint $table) {
                        $table->foreign('dinilai_oleh')->references('id')->on('users')->onDelete('set null');
                    });
                } catch (Exception $e) {
                    // Foreign key sudah ada, skip
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_tugas', function (Blueprint $table) {
            $table->dropForeign(['dinilai_oleh']);
            $table->dropColumn(['komentar', 'dinilai_oleh', 'dinilai_pada', 'revisi_ke']);
        });

        if (Schema::hasTable('kelompok_nilais')) {
            Schema::table('kelompok_nilais', function (Blueprint $table) {
                $table->dropForeign(['dinilai_oleh']);
                $table->dropColumn(['komentar', 'dinilai_oleh', 'dinilai_pada']);
            });
        }
    }
};
