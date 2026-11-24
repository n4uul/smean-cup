<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Team;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teams' => Team::count(),
            'pending_teams' => Team::where('status_verifikasi', 'pending')->count(),
            'approved_teams' => Team::where('status_verifikasi', 'approved')->count(),
            'total_matches' => MatchModel::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
