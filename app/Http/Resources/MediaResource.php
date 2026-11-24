<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// [RUBRIK - API RESOURCES]
// "Cetakan" JSON untuk Media, supaya semua endpoint membalas dengan format yang sama.

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'type'         => $this->type,
            'category'     => $this->category,      // accessor: Tontonan / Bacaan
            'genres'       => $this->genres,        // cast: JSON -> array
            'release_year' => $this->release_year,
            'rating'       => $this->rating,
            'creator'      => $this->creator,
            'total_units'  => $this->total_units,
            'unit_label'   => $this->unit_label,    // accessor: episode / chapter / volume

            // hanya muncul kalau media ini diambil LEWAT vibe list user (data pivot)
            'added_to_vibe_list_at' => $this->whenPivotLoaded('media_user', function () {
                return $this->pivot->created_at?->toDateTimeString();
            }),

            // hanya muncul kalau Controller memanggil withCount('users') / loadCount('users')
            'collected_by_count' => $this->whenCounted('users'),

            // whenLoaded: daftar user hanya muncul kalau relasi users sudah dimuat (eager loading)
            'collected_by' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
