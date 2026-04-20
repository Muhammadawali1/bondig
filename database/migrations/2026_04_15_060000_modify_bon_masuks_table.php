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
        Schema::table('bon_masuks', function (Blueprint $table) {
            // Change keterangan to tanggal_supplier
            $table->renameColumn('keterangan', 'tanggal_supplier');
            
            // Make tanggal_masuk nullable and remove default
            $table->timestamp('tanggal_masuk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_masuks', function (Blueprint $table) {
            // Revert changes
            $table->renameColumn('tanggal_supplier', 'keterangan');
            $table->timestamp('tanggal_masuk')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
    }
};
