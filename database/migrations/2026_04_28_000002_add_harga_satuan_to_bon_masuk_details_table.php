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
        Schema::table('bon_masuk_details', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->nullable()->after('jumlah_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_masuk_details', function (Blueprint $table) {
            $table->dropColumn('harga_satuan');
        });
    }
};
