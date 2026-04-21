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
        Schema::table('bon_barang_details', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['barang_id']);
            
            // Re-add foreign key with cascade delete
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_barang_details', function (Blueprint $table) {
            // Drop foreign key with cascade delete
            $table->dropForeign(['barang_id']);
            
            // Re-add foreign key without cascade delete
            $table->foreignId('barang_id')->constrained('barangs');
        });
    }
};
