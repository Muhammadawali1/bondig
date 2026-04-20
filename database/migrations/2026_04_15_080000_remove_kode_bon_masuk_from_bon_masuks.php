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
            $table->dropColumn('kode_bon_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_masuks', function (Blueprint $table) {
            $table->string('kode_bon_masuk')->unique()->after('id');
        });
    }
};
