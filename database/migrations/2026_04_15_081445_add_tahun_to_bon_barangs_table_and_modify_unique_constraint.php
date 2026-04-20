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
        Schema::table('bon_barangs', function (Blueprint $table) {
            // Add tahun column
            $table->year('tahun')->nullable()->after('divisi');
        });

        // Populate tahun column from existing tanggal_pengajuan
        DB::statement('UPDATE bon_barangs SET tahun = YEAR(tanggal_pengajuan) WHERE tahun IS NULL');

        // Drop existing unique constraint on kode_bon
        Schema::table('bon_barangs', function (Blueprint $table) {
            $table->dropUnique('bon_barangs_kode_bon_unique');
        });

        // Add composite unique constraint on (tahun, kode_bon)
        Schema::table('bon_barangs', function (Blueprint $table) {
            $table->unique(['tahun', 'kode_bon'], 'bon_barangs_tahun_kode_bon_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_barangs', function (Blueprint $table) {
            // Drop composite unique constraint
            $table->dropUnique('bon_barangs_tahun_kode_bon_unique');
        });

        // Add back original unique constraint on kode_bon
        Schema::table('bon_barangs', function (Blueprint $table) {
            $table->unique('kode_bon', 'bon_barangs_kode_bon_unique');
        });

        // Drop tahun column
        Schema::table('bon_barangs', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
