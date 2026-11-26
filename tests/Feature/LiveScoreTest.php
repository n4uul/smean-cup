<?php

namespace Tests\Feature;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_api_returns_matches_with_team_details(): void
    {
        $match = $this->createMatch('live');

        $this->getJson('/api/live-scores')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.id', $match->id)
            ->assertJsonPath('data.0.status_match', 'live')
            ->assertJsonPath('data.0.home_team.nama_tim', 'Home Team')
            ->assertJsonPath('data.0.home_team.kategori', 'putra')
            ->assertJsonPath('data.0.away_team.nama_tim', 'Away Team')
            ->assertJsonPath('data.0.away_team.kategori', 'putra')
            ->assertJsonPath('data.0.waktu_tanding_formatted', '10 Mar 2026 · 08:30 WIB');
    }

    public function test_public_live_api_returns_only_live_matches(): void
    {
        $liveMatch = $this->createMatch('live');
        $this->createMatch('upcoming', ' 2');

        $this->getJson('/api/live-scores/active')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $liveMatch->id);
    }

    public function test_public_schedule_page_displays_match_information(): void
    {
        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertSee('Jadwal & Live Score')
            ->assertSee('Semua')
            ->assertSee('Putra')
            ->assertSee('Putri')
            ->assertSee('matches-container')
            ->assertSee('/api/live-scores')
            ->assertSee('voteHype')
            ->assertSee('X-CSRF-TOKEN')
            ->assertSee('Aktif (5s)');
    }

    public function test_public_homepage_shows_approved_teams_and_match_schedule(): void
    {
        $match = $this->createMatch('upcoming');
        $this->createTeam('Putri United', 'approved', 'putri');
        $this->createTeam('Pending Team', 'pending');

        $this->get('/')
            ->assertOk()
            ->assertSee('Home Team')
            ->assertSee('Away Team')
            ->assertSee('Putri United')
            ->assertSee('Putra')
            ->assertSee('Putri')
            ->assertSee('10 Mar 2026 · 08:30 WIB')
            ->assertSee($match->babak)
            ->assertDontSee('Pending Team');
    }

    private function createMatch(string $status, string $suffix = '', string $kategori = 'putra'): MatchModel
    {
        $home = $this->createTeam('Home Team'.$suffix, 'approved', $kategori);
        $away = $this->createTeam('Away Team'.$suffix, 'approved', $kategori);

        return MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-03-10 08:30:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
            'skor_home' => 1,
            'skor_away' => 0,
            'status_match' => $status,
        ]);
    }

    private function createTeam(string $name, string $status = 'approved', string $kategori = 'putra'): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'kategori' => $kategori,
            'asal_sekolah' => 'SMKN 1 Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => $status,
        ]);
    }
}
