<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchModel extends Model
{
    protected $table = 'matches';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'hype_home_count' => 'integer',
            'hype_away_count' => 'integer',
        ];
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
