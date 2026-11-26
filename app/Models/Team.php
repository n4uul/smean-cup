<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    public const KATEGORI_PUTRA = 'putra';
    public const KATEGORI_PUTRI = 'putri';
    public const MAX_PLAYERS = 14;

    protected $fillable = [
        'user_id',
        'nama_tim',
        'kategori',
        'asal_sekolah',
        'nama_pelatih',
        'no_wa',
        'logo_tim',
        'berkas_syarat',
        'status_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'kategori' => 'string',
        ];
    }

    public function getKategoriLabelAttribute(): string
    {
        return $this->kategori === self::KATEGORI_PUTRI ? 'Putri' : 'Putra';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
