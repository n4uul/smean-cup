<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HypeVoteController extends Controller
{
    public function store(Request $request, MatchModel $match): JsonResponse
    {
        $validated = $request->validate([
            'team_side' => ['required', 'string', 'in:home,away'],
        ]);

        $counterColumn = $validated['team_side'] === 'home'
            ? 'hype_home_count'
            : 'hype_away_count';

        DB::table('matches')
            ->where('id', $match->getKey())
            ->increment($counterColumn);

        $match->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'match_id' => $match->getKey(),
                'hype_home_count' => (int) $match->hype_home_count,
                'hype_away_count' => (int) $match->hype_away_count,
            ],
        ]);
    }
}
