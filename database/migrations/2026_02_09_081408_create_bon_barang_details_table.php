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
        Schema::create('bon_barang_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_barang_id')->constrained('bon_barangs')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('jumlah_diminta');
            $table->integer('jumlah_disetujui')->nullable();
            $table->enum('status_detail', ['menunggu', 'disetujui', 'ditolak', 'sebagian'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_barang_details');
    }
};
