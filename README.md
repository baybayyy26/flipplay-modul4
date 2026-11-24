# FlipPlay API — Modul 4 (Laravel 12 REST API)

> *Watch. Read. Find your vibe.*

Lanjutan simulasi FlipPlay Modul 3 (PHP OOP di terminal) menjadi **REST API Laravel 12**. Entity Modul 3 tetap dipakai, tapi sekarang tersimpan di database MySQL:

| Modul 3 (OOP terminal) | Modul 4 (Laravel REST API) |
| --- | --- |
| `class Media` + `Anime`, `Drama`, `Manga`, `LightNovel` | Model `Media` + kolom `type` |
| `class User` + array `$vibeList` | Model `User` + relasi `belongsToMany` lewat pivot `media_user` |
| `VibeGrid::searchByTitle()` | `GET /api/media?search=...` (query scope) |
| `User::addToVibeList()` (tolak kalau sudah ada) | `POST /api/users/{user}/vibe-list` (409 kalau sudah ada) |

## Requirement

- PHP 8.2+ (extension `zip` aktif), Composer
- MySQL (Laragon / XAMPP)

## Setup

```bash
composer install
cp .env.example .env          # Windows CMD: copy .env.example .env
php artisan key:generate
php artisan migrate --seed     # buat database flipplay_modul4 -> jawab "yes"
php artisan route:list --path=api
php artisan serve
```

Seeder mengisi 2 user (`baybay` id 1, `vibetester` id 2) dan 7 media dari simulasi Modul 3. Ulang dari nol: `php artisan migrate:fresh --seed`.

## Endpoint

Base URL: `http://127.0.0.1:8000/api`

| Method | Endpoint | Keterangan | Status |
| --- | --- | --- | --- |
| GET | `/media` | Semua media (paginate 10). Filter: `?search=judul`, `?type=anime` | 200 |
| GET | `/media/{id}` | Detail media + user yang menyimpannya | 200 / 404 |
| POST | `/media` | Tambah media | 201 / 422 |
| PATCH | `/media/{id}` | Ubah sebagian data media | 200 / 404 / 422 |
| DELETE | `/media/{id}` | Hapus media | 200 / 404 |
| GET | `/users` | Semua user + jumlah vibe list | 200 |
| GET | `/users/{id}` | Detail user + isi vibe list | 200 / 404 |
| GET | `/users/{id}/vibe-list` | Isi vibe list user | 200 / 404 |
| POST | `/users/{id}/vibe-list` | **Custom action (attach)**: tambah media ke vibe list, body `{ "media_id": 1 }` | 201 / 404 / 409 / 422 |
| DELETE | `/users/{id}/vibe-list/{mediaId}` | **Custom action (detach)**: hapus media dari vibe list | 200 / 404 |

Contoh body `POST /media`:

```json
{
  "title": "Spy x Family",
  "type": "anime",
  "genres": ["Action", "Comedy", "Family"],
  "release_year": 2022,
  "rating": 8.4,
  "creator": "Wit Studio",
  "total_units": 25
}
```

`type`: `anime`, `drama`, `manga`, `light_novel`. `rating` 0–10 (opsional), `creator` = studio/negara/penulis (opsional), `total_units` = jumlah episode/chapter/volume (opsional).

Koleksi Postman siap impor: [`postman/FlipPlay-Modul4.postman_collection.json`](postman/FlipPlay-Modul4.postman_collection.json).
