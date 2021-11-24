<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'Agus',
            'divisi_id' => '2',
            'email' => 'agus@gmail.com',
            'username' => 'agus01',
            'password' => Hash::make('12345678'),
            'status' => 'offline',
            'foto' => '',
        ]);
    }
}
