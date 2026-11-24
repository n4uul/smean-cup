<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\JsonResponse;

class LiveScoreController extends Controller
{
    public function index(): JsonResponse
    {
        $matches = MatchModel::with([
            'homeTeam:id,nama_tim,asal_sekolah',
            'awayTeam:id,nama_tim,asal_sekolah',
        ])->orderBy('waktu_tanding')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal dan live score SMEAN Cup',
            'data' => $matches,
        ]);
    }

    public function liveMatches(): JsonResponse
    {
        $matches = MatchModel::with([
            'homeTeam:id,nama_tim,asal_sekolah',
            'awayTeam:id,nama_tim,asal_sekolah',
        ])->where('status_match', 'live')
            ->orderBy('waktu_tanding')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pertandingan yang sedang LIVE',
            'data' => $matches,
        ]);
    }
}
