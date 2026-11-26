<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchModel extends Model
{
    protected $table = 'matches';

    protected $guarded = ['id'];

    protected $appends = [
        'waktu_tanding_formatted',
    ];

    protected function casts(): array
    {
        return [
            'hype_home_count' => 'integer',
            'hype_away_count' => 'integer',
        ];
    }

    public function getWaktuTandingFormattedAttribute(): string
    {
        if (! $this->waktu_tanding) {
            return '-';
        }

        return Carbon::parse($this->waktu_tanding)
            ->locale('id')
            ->translatedFormat('d M Y · H:i') . ' WIB';
    }

    public function getKategoriAttribute(): string
    {
        return $this->homeTeam?->kategori ?? $this->awayTeam?->kategori ?? Team::KATEGORI_PUTRA;
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }
}
