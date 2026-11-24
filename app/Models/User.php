<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model User = class User dari Modul 3 (pemilik My Vibe List).
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    // Kolom rahasia tidak pernah ikut tampil di JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // [RUBRIK - ELOQUENT RELATIONSHIPS] Many-to-Many:
    // satu User punya BANYAK Media di vibe list-nya (lewat pivot media_user)
    // -> pengganti array $vibeList di Modul 3
    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)->withTimestamps();
    }
}
