<?php

namespace Tests\Feature\Admin;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_registration_and_match_totals(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $home = $this->createTeam('Tim Pending', 'pending');
        $away = $this->createTeam('Tim Approved', 'approved');
        $this->createTeam('Tim Rejected', 'rejected');

        MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats', [
                'total_teams' => 3,
                'pending_teams' => 1,
                'approved_teams' => 1,
                'total_matches' => 1,
            ]);
    }

    public function test_participant_cannot_access_admin_overview(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    private function createTeam(string $name, string $status): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'asal_sekolah' => 'SMK Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => $status,
        ]);
    }
}
