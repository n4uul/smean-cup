<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    public const POSISI_LIST = [
        'Kiper',
        'Anchor',
        'Flank',
        'Pivot',
    ];

    protected $fillable = [
        'team_id',
        'nama_lengkap',
        'nomor_punggung',
        'posisi',
        'nisn',
        'jumlah_gol',
        'jumlah_kartu_kuning',
        'jumlah_kartu_merah',
    ];

    protected function casts(): array
    {
        return [
            'nomor_punggung' => 'integer',
            'jumlah_gol' => 'integer',
            'jumlah_kartu_kuning' => 'integer',
            'jumlah_kartu_merah' => 'integer',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeTopScorers(Builder $query, int $limit = 10): Builder
    {
        return $query
            ->with('team:id,nama_tim,asal_sekolah,kategori,logo_tim')
            ->where('jumlah_gol', '>', 0)
            ->whereHas('team', fn (Builder $teamQuery) => $teamQuery->where('status_verifikasi', 'approved'))
            ->orderByDesc('jumlah_gol')
            ->orderBy('nama_lengkap')
            ->limit($limit);
    }
}
