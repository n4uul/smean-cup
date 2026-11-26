<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopScorerLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_display_top_10_scorers_from_approved_teams(): void
    {
        $approvedTeam = $this->createTeam('Garut Eagles', 'SMPN 1 Garut', 'approved');
        $pendingTeam = $this->createTeam('Pending Falcons', 'SMPN 2 Garut', 'pending');

        for ($i = 1; $i <= 11; $i++) {
            Player::create([
                'team_id' => $approvedTeam->id,
                'nama_lengkap' => sprintf('Striker %02d', $i),
                'nomor_punggung' => $i,
                'posisi' => 'Pivot',
                'jumlah_gol' => 20 - $i,
            ]);
        }

        Player::create([
            'team_id' => $approvedTeam->id,
            'nama_lengkap' => 'Kiper Tanpa Gol',
            'nomor_punggung' => 99,
            'posisi' => 'Kiper',
            'jumlah_gol' => 0,
        ]);

        Player::create([
            'team_id' => $pendingTeam->id,
            'nama_lengkap' => 'Pemain Belum Verified',
            'nomor_punggung' => 88,
            'posisi' => 'Pivot',
            'jumlah_gol' => 50,
        ]);

        foreach (['/', route('public.schedule')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Statistik')
                ->assertSee('Top Scorer')
                ->assertSee('Striker 01')
                ->assertSee('#1')
                ->assertSee('Striker 10')
                ->assertSee('Garut Eagles')
                ->assertSee('SMPN 1 Garut')
                ->assertDontSee('Striker 11')
                ->assertDontSee('Kiper Tanpa Gol')
                ->assertDontSee('Pemain Belum Verified');
        }
    }

    public function test_public_pages_show_empty_state_when_no_goals_recorded(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Belum ada gol tercatat untuk kategori Putra')
            ->assertSee('Belum ada gol tercatat untuk kategori Putri');

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertSee('Belum ada gol tercatat untuk kategori Putra')
            ->assertSee('Belum ada gol tercatat untuk kategori Putri');
    }

    private function createTeam(string $name, string $school, string $status): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'kategori' => 'putra',
            'asal_sekolah' => $school,
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => $status,
        ]);
    }
}
