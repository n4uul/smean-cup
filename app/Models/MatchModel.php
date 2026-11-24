<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchModel extends Model
{
    protected $table = 'matches';

    protected $guarded = ['id'];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }
}
