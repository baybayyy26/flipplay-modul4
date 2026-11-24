<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// [RUBRIK - ELOQUENT RELATIONSHIPS: PIVOT TABLE]
// Pivot table "media_user" = My Vibe List (koleksi media milik user).
// Nama mengikuti konvensi Laravel: nama 2 Model bentuk tunggal, urut abjad
// -> "media_user" (bukan "user_media").  Lihat modul bagian 4.3.

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_user', function (Blueprint $table) {
            $table->id();
            // foreign key ke users.id dan media.id
            // cascadeOnDelete: kalau user/media dihapus, baris koleksinya ikut terhapus
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->timestamps();                            // created_at = kapan ditambahkan

            // satu media hanya boleh SEKALI di vibe list user yang sama
            $table->unique(['user_id', 'media_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_user');
    }
};
