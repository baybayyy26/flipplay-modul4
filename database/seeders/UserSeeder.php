<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// User contoh. "baybay" = username yang sama dengan simulasi Modul 3
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Baybay',
            'username' => 'baybay',
            'email'    => 'baybay@flipplay.test',
            'password' => 'password',   // otomatis di-hash (cast 'hashed' di Model User)
        ]);

        User::create([
            'name'     => 'Vibe Tester',
            'username' => 'vibetester',
            'email'    => 'tester@flipplay.test',
            'password' => 'password',
        ]);
    }
}
