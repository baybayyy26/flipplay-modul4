<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

// [RUBRIK - COMPLETE CRUD API]
// 5 method standar CRUD untuk entity Media (modul Tabel 4.1):
// index (GET semua), show (GET satu), store (POST), update (PATCH), destroy (DELETE)

class MediaController extends Controller
{
    // GET /api/media            -> daftar semua media (10 per halaman)
    // GET /api/media?search=man -> Unified Search berdasarkan judul (query scope)
    // GET /api/media?type=anime -> filter berdasarkan jenis
    public function index(Request $request)
    {
        $media = Media::query()
            ->search($request->query('search'))   // scopeSearch() di Model Media
            ->ofType($request->query('type'))     // scopeOfType() di Model Media
            ->withCount('users')                  // berapa user yang menyimpan media ini
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();                  // ?search ikut terbawa di link halaman berikutnya

        return MediaResource::collection($media);
    }

    // GET /api/media/{media} -> detail satu media + user yang menyimpannya
    // Route Model Binding: id tidak ada -> otomatis 404
    public function show(Media $media)
    {
        // eager loading: relasi users dimuat sekaligus -> muncul di "collected_by"
        return new MediaResource($media->load('users')->loadCount('users'));
    }

    // POST /api/media -> tambah media baru. Validasi gagal -> otomatis 422
    public function store(MediaRequest $request)
    {
        $media = Media::create($request->validated());

        // refresh(): ambil ulang dari database supaya nilai default (rating 0) ikut tampil
        return (new MediaResource($media->refresh()))
            ->response()
            ->setStatusCode(201);                 // 201 Created
    }

    // PATCH /api/media/{media} -> ubah sebagian data media
    public function update(MediaRequest $request, Media $media)
    {
        $media->update($request->validated());

        return new MediaResource($media);         // 200 OK
    }

    // DELETE /api/media/{media} -> hapus media
    // Baris di vibe list (pivot) ikut terhapus karena cascadeOnDelete di migration
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([
            'message' => "Media '{$media->title}' berhasil dihapus",
        ]);
    }
}
