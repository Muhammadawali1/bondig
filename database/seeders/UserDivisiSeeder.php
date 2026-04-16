<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserDivisiSeeder extends Seeder
{
    public function run(): void
    {
        // Update existing users dengan divisi
        $users = [
            [
                'name' => 'Myhawnk',
                'nip' => '0000',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'divisi' => 'IT',
            ],
            [
                'name' => 'Budi',
                'nip' => '1111', 
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'divisi' => 'IT',
            ],
            [
                'name' => 'Eang',
                'nip' => '0002',
                'password' => Hash::make('password'),
                'role' => 'gudang',
                'divisi' => 'Gudang',
            ],
            [
                'name' => 'Diana',
                'nip' => '2222',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'divisi' => 'Akuntansi',
            ],
            [
                'name' => 'Eko',
                'nip' => '3333',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'divisi' => 'Akuntansi',
            ],
            [
                'name' => 'Administrator',
                'nip' => '9999',
                'password' => Hash::make('password'),
                'role' => 'administrator',
                'divisi' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['nip' => $userData['nip']],
                $userData
            );
        }
    }
}
