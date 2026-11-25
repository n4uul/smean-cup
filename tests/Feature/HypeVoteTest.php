<?php

namespace Tests\Feature;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HypeVoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_votes_increment_match_counters_and_are_returned_by_live_scores(): void
    {
        $match = $this->createMatch();

        $this->postJson(route('api.matches.hype', $match), ['team_side' => 'home'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.match_id', $match->id)
            ->assertJsonPath('data.hype_home_count', 1)
            ->assertJsonPath('data.hype_away_count', 0);

        $this->postJson(route('api.matches.hype', $match), ['team_side' => 'away'])
            ->assertOk()
            ->assertJsonPath('data.hype_home_count', 1)
            ->assertJsonPath('data.hype_away_count', 1);

        $this->getJson('/api/live-scores')
            ->assertOk()
            ->assertJsonPath('data.0.hype_home_count', 1)
            ->assertJsonPath('data.0.hype_away_count', 1);
    }

    public function test_vote_rejects_an_invalid_team_side(): void
    {
        $match = $this->createMatch();

        $this->postJson(route('api.matches.hype', $match), ['team_side' => 'middle'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('team_side');

        $this->assertSame(0, $match->fresh()->hype_home_count);
        $this->assertSame(0, $match->fresh()->hype_away_count);
    }

    private function createMatch(): MatchModel
    {
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');

        return MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
            'skor_home' => 0,
            'skor_away' => 0,
            'status_match' => 'upcoming',
        ]);
    }

    private function createTeam(string $name): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'asal_sekolah' => 'SMKN 1 Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'approved',
        ]);
    }
}
