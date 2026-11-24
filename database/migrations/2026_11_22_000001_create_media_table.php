<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// [RUBRIK - ELOQUENT RELATIONSHIPS: MIGRATION]
// Table "media" = rak katalog FlipPlay (The Vibe Grid di Modul 3).
// Di Modul 3 setiap jenis punya class sendiri (Anime, Drama, Manga, LightNovel);
// di database cukup SATU table dengan kolom "type" sebagai pembeda.

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();               // judul tidak boleh dobel
            $table->enum('type', ['anime', 'drama', 'manga', 'light_novel']);
            $table->json('genres');                          // banyak genre -> disimpan sebagai JSON
            $table->unsignedSmallInteger('release_year');
            $table->decimal('rating', 3, 1)->default(0);     // 0.0 - 10.0
            $table->string('creator')->nullable();           // studio / negara / penulis
            $table->unsignedInteger('total_units')->nullable(); // jumlah episode / chapter / volume
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
