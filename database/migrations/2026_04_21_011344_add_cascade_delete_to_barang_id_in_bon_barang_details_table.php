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
            // Drop existing foreign key constraint if it exists
            $conName = 'bon_barang_details_barang_id_foreign';
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE bon_barang_details DROP FOREIGN KEY IF EXISTS $conName");
            
            // Re-add foreign key with cascade delete (column already exists)
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
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
