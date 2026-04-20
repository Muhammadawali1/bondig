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
        Schema::create('bon_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bon_masuk')->unique();
            $table->foreignId('gudang_id')->constrained('users');
            $table->string('supplier')->nullable();
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_masuk')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_masuks');
    }
};
