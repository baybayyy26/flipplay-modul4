<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToVibeListRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

// UserController = pemilik My Vibe List.
// Selain melihat user, di sini ada CUSTOM ACTION (di luar CRUD standar)
// yang mengubah relasi Media <-> User lewat attach() / detach().

class UserController extends Controller
{
    // GET /api/users -> daftar user + jumlah media di vibe list masing-masing
    public function index()
    {
        $users = User::withCount('media')->orderBy('username')->paginate(10);

        return UserResource::collection($users);
    }

    // GET /api/users/{user} -> detail user + isi vibe list-nya (eager loading)
    public function show(User $user)
    {
        return new UserResource($user->load('media')->loadCount('media'));
    }

    // GET /api/users/{user}/vibe-list -> isi vibe list saja
    public function vibeList(User $user)
    {
        return MediaResource::collection($user->media()->orderByPivot('created_at')->get());
    }

    // [RUBRIK - CUSTOM ACTION: ATTACH]
    // POST /api/users/{user}/vibe-list   body: { "media_id": 1 }
    public function addToVibeList(AddToVibeListRequest $request, User $user)
    {
        // 409 Conflict: media sudah ada di vibe list user ini (modul 4.6, Figure 4.16)
        if ($user->media()->where('media_id', $request->media_id)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada di Vibe List',
            ], 409);
        }

        try {
            // attach() = tambah 1 baris baru di pivot table media_user
            $user->media()->attach($request->media_id);
        } catch (UniqueConstraintViolationException $e) {
            // Pengaman race condition (modul 4.4): kalau 2 request sama masuk bersamaan,
            // unique index di database menolak yang kedua -> tetap dibalas 409
            return response()->json([
                'message' => 'Media sudah ada di Vibe List',
            ], 409);
        }

        // ambil lagi lewat relasi supaya data pivot (added_to_vibe_list_at) ikut tampil
        $media = $user->media()->find($request->media_id);

        return response()->json([
            'message' => "'{$media->title}' berhasil ditambahkan ke Vibe List {$user->username}",
            'data'    => new MediaResource($media),
        ], 201);
    }

    // [RUBRIK - CUSTOM ACTION: DETACH]
    // DELETE /api/users/{user}/vibe-list/{media}
    public function removeFromVibeList(User $user, Media $media)
    {
        // detach() = hapus baris pivot; hasilnya = jumlah baris yang terhapus
        $removed = $user->media()->detach($media->id);

        // 0 baris terhapus = media memang tidak ada di vibe list user ini
        if ($removed === 0) {
            return response()->json([
                'message' => 'Media tidak ada di Vibe List user ini',
            ], 404);
        }

        return response()->json([
            'message' => "'{$media->title}' berhasil dihapus dari Vibe List {$user->username}",
        ]);
    }
}
