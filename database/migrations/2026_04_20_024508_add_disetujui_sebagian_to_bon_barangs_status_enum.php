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
        DB::statement("ALTER TABLE bon_barangs MODIFY COLUMN status ENUM('menunggu_atasan', 'menunggu_gudang', 'disetujui', 'disetujui_sebagian', 'ditolak', 'selesai') DEFAULT 'menunggu_atasan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE bon_barangs MODIFY COLUMN status ENUM('menunggu_atasan', 'menunggu_gudang', 'disetujui', 'ditolak', 'selesai') DEFAULT 'menunggu_atasan'");
    }
};
