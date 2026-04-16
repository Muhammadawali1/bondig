<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus user yang nipnya mengandung @ (nip lama) 
        // tapi hanya yang tidak memiliki bon_barangs terkait
        $usersToDelete = DB::table('users')
            ->where('nip', 'like', '%@%')
            ->whereNotIn('nip', ['1001', '2001', '3001', '1002', '2002']) // Kecuali user NIP
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('bon_barangs')
                    ->whereRaw('bon_barangs.pegawai_id = users.id');
            })
            ->pluck('id');
            
        DB::table('users')->whereIn('id', $usersToDelete)->delete();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // Tidak ada rollback untuk cleanup
    }
};
