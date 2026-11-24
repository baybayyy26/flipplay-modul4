<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // php artisan migrate --seed  /  php artisan migrate:fresh --seed
    public function run(): void
    {
        $this->call([
            UserSeeder::class,   // 2 user: baybay (id 1), vibetester (id 2)
            MediaSeeder::class,  // 7 media dari simulasi Modul 3 (id 1-7)
        ]);
        // Vibe list sengaja dibiarkan kosong supaya bisa didemokan lewat Postman
    }
}
