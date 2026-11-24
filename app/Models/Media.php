<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// Model Media = class Media dari Modul 3, tapi sekarang terhubung ke table "media".
class Media extends Model
{
    // Laravel menebak nama table dari nama Model. Ditulis jelas supaya pasti "media".
    protected $table = 'media';

    // Kolom yang boleh diisi lewat Media::create([...]) / update([...]) (mass assignment)
    protected $fillable = [
        'title',
        'type',
        'genres',
        'release_year',
        'rating',
        'creator',
        'total_units',
    ];

    // Casting: genres (JSON di database) otomatis jadi array PHP, rating jadi float
    protected function casts(): array
    {
        return [
            'genres'       => 'array',
            'release_year' => 'integer',
            'rating'       => 'float',
            'total_units'  => 'integer',
        ];
    }

    // [RUBRIK - ELOQUENT RELATIONSHIPS] Many-to-Many:
    // satu Media bisa ada di vibe list BANYAK User (lewat pivot media_user)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // ---------- ACCESSOR & MUTATOR (modul 2.4) ----------

    // Mutator: judul dirapikan SEBELUM disimpan ("  Haikyuu!!  " -> "Haikyuu!!")
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => trim(preg_replace('/\s+/', ' ', $value)),
        );
    }

    // Accessor: kategori dihitung SAAT dibaca (seperti getCategory() di Modul 3)
    protected function category(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->type, ['anime', 'drama']) ? 'Tontonan' : 'Bacaan',
        );
    }

    // Accessor: satuan total_units sesuai jenis media
    protected function unitLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->type) {
                'anime', 'drama' => 'episode',
                'manga'          => 'chapter',
                'light_novel'    => 'volume',
                default          => 'unit',
            },
        );
    }

    // ---------- QUERY SCOPE (modul 2.4) ----------

    // Media::search('man') -> cari judul yang mengandung "man" (Unified Search di Modul 3)
    public function scopeSearch(Builder $query, ?string $keyword): void
    {
        if (filled($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
    }

    // Media::ofType('anime') -> filter berdasarkan jenis
    public function scopeOfType(Builder $query, ?string $type): void
    {
        if (filled($type)) {
            $query->where('type', $type);
        }
    }
}
