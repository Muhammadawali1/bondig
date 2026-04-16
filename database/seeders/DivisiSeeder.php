<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['nama' => 'IT'],
            ['nama' => 'Akuntansi'],
            ['nama' => 'SDM'],
            ['nama' => 'Produksi'],
            ['nama' => 'Marketing'],
            ['nama' => 'Gudang'],
        ];

        foreach ($divisions as $division) {
            Divisi::firstOrCreate(
                ['nama' => $division['nama']],
                $division
            );
        }
    }
}
