<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// [RUBRIK - API RESOURCES]
// "Cetakan" JSON untuk User. Password & email tidak ditampilkan.

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'username' => $this->username,

            // hanya muncul kalau user ini diambil lewat relasi media->users (data pivot)
            'added_at' => $this->whenPivotLoaded('media_user', function () {
                return $this->pivot->created_at?->toDateTimeString();
            }),

            'vibe_list_count' => $this->whenCounted('media'),

            // whenLoaded: vibe list hanya muncul kalau relasi media sudah dimuat
            'vibe_list' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
