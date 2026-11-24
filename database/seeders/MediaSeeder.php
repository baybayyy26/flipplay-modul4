<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

// Data awal = judul-judul yang sama dengan simulasi FlipPlay Modul 3
class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Chainsaw Man', 'type' => 'anime', 'genres' => ['Action', 'Dark Fantasy'], 'release_year' => 2022, 'rating' => 8.5, 'creator' => 'MAPPA', 'total_units' => 12],
            ['title' => 'Haikyuu!!', 'type' => 'anime', 'genres' => ['Sports', 'Comedy'], 'release_year' => 2014, 'rating' => 8.7, 'creator' => 'Production I.G', 'total_units' => 25],
            ['title' => 'Your Lie in April', 'type' => 'anime', 'genres' => ['Drama', 'Music', 'Romance'], 'release_year' => 2014, 'rating' => 8.6, 'creator' => 'A-1 Pictures', 'total_units' => 22],
            ['title' => "Frieren: Beyond Journey's End", 'type' => 'anime', 'genres' => ['Adventure', 'Fantasy', 'Drama'], 'release_year' => 2023, 'rating' => 9.3, 'creator' => 'Madhouse', 'total_units' => 28],
            ['title' => 'Reply 1988', 'type' => 'drama', 'genres' => ['Drama', 'Comedy', 'Family'], 'release_year' => 2015, 'rating' => 9.2, 'creator' => 'Korea Selatan', 'total_units' => 20],
            ['title' => 'Fullmetal Alchemist', 'type' => 'manga', 'genres' => ['Action', 'Adventure', 'Fantasy'], 'release_year' => 2001, 'rating' => 9.1, 'creator' => 'Hiromu Arakawa', 'total_units' => 108],
            ['title' => 'Toradora!', 'type' => 'light_novel', 'genres' => ['Romance', 'Comedy'], 'release_year' => 2006, 'rating' => 8.0, 'creator' => 'Yuyuko Takemiya', 'total_units' => 10],
        ];

        foreach ($items as $item) {
            Media::create($item);
        }
    }
}
