<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// [RUBRIK - FORM REQUEST VALIDATION & 422]
// Dipakai untuk POST /api/media (tambah) dan PATCH /api/media/{media} (ubah).
// Kalau ada aturan yang dilanggar, Laravel otomatis membalas 422 + pesan di bawah,
// sebelum kode Controller sempat dijalankan.

class MediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // POST = semua kolom wajib (required)
        // PATCH = hanya kolom yang dikirim yang dicek (sometimes) - modul Figure 3.10
        $isPatch = $this->isMethod('PATCH');
        $wajib   = $isPatch ? 'sometimes' : 'required';

        return [
            // judul wajib & tidak boleh sama dengan judul lain.
            // Saat PATCH, judul milik media itu sendiri diabaikan (ignore) supaya tidak dianggap dobel.
            'title' => [
                $wajib, 'string', 'max:255',
                Rule::unique('media', 'title')->ignore($this->route('media')),
            ],
            'type'         => [$wajib, Rule::in(['anime', 'drama', 'manga', 'light_novel'])],
            'genres'       => [$wajib, 'array', 'min:1'],
            'genres.*'     => ['string', 'max:50', 'distinct'],
            'release_year' => [$wajib, 'integer', 'between:1900,' . (now()->year + 1)],
            'rating'       => ['sometimes', 'numeric', 'between:0,10'],
            'creator'      => ['nullable', 'string', 'max:255'],
            'total_units'  => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'Judul media wajib diisi',
            'title.unique'          => 'Judul media sudah ada di FlipPlay',
            'type.required'         => 'Jenis media wajib diisi',
            'type.in'               => 'Jenis media harus salah satu dari: anime, drama, manga, light_novel',
            'genres.required'       => 'Minimal satu genre wajib diisi',
            'genres.array'          => 'Genre harus berupa array, contoh: ["Action", "Comedy"]',
            'genres.min'            => 'Minimal satu genre wajib diisi',
            'genres.*.distinct'     => 'Genre tidak boleh dobel',
            'release_year.required' => 'Tahun rilis wajib diisi',
            'release_year.between'  => 'Tahun rilis harus antara 1900 sampai ' . (now()->year + 1),
            'rating.between'        => 'Rating harus antara 0 sampai 10',
            'total_units.min'       => 'Jumlah episode/chapter/volume minimal 1',
        ];
    }
}
