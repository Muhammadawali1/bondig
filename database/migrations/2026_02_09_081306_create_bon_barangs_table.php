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
        Schema::create('bon_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bon')->unique();
            $table->foreignId('pegawai_id')->constrained('users');
            $table->enum('status', ['menunggu_atasan', 'menunggu_gudang', 'disetujui', 'ditolak', 'selesai'])->default('menunggu_atasan');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_pengajuan')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('tanggal_atasan')->nullable();
            $table->timestamp('tanggal_gudang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_barangs');
    }
};
