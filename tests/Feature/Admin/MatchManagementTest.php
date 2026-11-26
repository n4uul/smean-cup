<?php

namespace Tests\Feature\Admin;

use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_match_management_page(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');

        MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.matches.index'))
            ->assertOk()
            ->assertSee('Home Team')
            ->assertSee('Away Team')
            ->assertSee('Penyisihan');
    }

    public function test_admin_can_schedule_a_match_between_approved_teams(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');

        $this->actingAs($admin)
            ->post(route('admin.matches.store'), [
                'team_home_id' => $home->id,
                'team_away_id' => $away->id,
                'waktu_tanding' => '2026-12-01T10:00',
                'lapangan' => 'Lapangan Utama',
                'babak' => 'Penyisihan',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('matches', [
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'status_match' => 'upcoming',
            'skor_home' => 0,
            'skor_away' => 0,
        ]);
    }

    public function test_admin_cannot_schedule_a_match_with_the_same_team_twice(): void
    {
        $admin = $this->createAdmin();
        $team = $this->createTeam('Only Team');

        $this->actingAs($admin)
            ->from(route('admin.matches.index'))
            ->post(route('admin.matches.store'), [
                'team_home_id' => $team->id,
                'team_away_id' => $team->id,
                'waktu_tanding' => '2026-12-01T10:00',
                'lapangan' => 'Lapangan Utama',
                'babak' => 'Penyisihan',
            ])
            ->assertSessionHasErrors('team_home_id');

        $this->assertDatabaseCount('matches', 0);
    }

    public function test_admin_cannot_schedule_a_match_between_different_categories(): void
    {
        $admin = $this->createAdmin();
        $putra = $this->createTeam('Putra Team', 'putra');
        $putri = $this->createTeam('Putri Team', 'putri');

        $this->actingAs($admin)
            ->from(route('admin.matches.index'))
            ->post(route('admin.matches.store'), [
                'team_home_id' => $putra->id,
                'team_away_id' => $putri->id,
                'waktu_tanding' => '2026-12-01T10:00',
                'lapangan' => 'Lapangan Utama',
                'babak' => 'Penyisihan',
            ])
            ->assertSessionHasErrors('team_away_id');

        $this->assertDatabaseCount('matches', 0);
    }

    public function test_admin_can_schedule_a_match_between_putri_teams(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Putri A', 'putri');
        $away = $this->createTeam('Putri B', 'putri');

        $this->actingAs($admin)
            ->post(route('admin.matches.store'), [
                'team_home_id' => $home->id,
                'team_away_id' => $away->id,
                'waktu_tanding' => '2026-03-10T08:30',
                'lapangan' => 'Lapangan Utama',
                'babak' => 'Penyisihan',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('matches', [
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
        ]);
    }

    public function test_admin_can_update_match_score_and_status(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.matches.update', $match), [
                'skor_home' => 2,
                'skor_away' => 1,
                'status_match' => 'live',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('matches', [
            'id' => $match->id,
            'skor_home' => 2,
            'skor_away' => 1,
            'status_match' => 'live',
        ]);
    }

    public function test_admin_can_adjust_live_score_without_allowing_negative_values(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->patchJson(route('admin.matches.updateScore', $match), [
                'side' => 'home',
                'action' => 'increment',
            ])
            ->assertOk()
            ->assertJsonPath('data.skor_home', 1);

        $this->patchJson(route('admin.matches.updateScore', $match), [
            'side' => 'home',
            'action' => 'decrement',
        ])
            ->assertOk()
            ->assertJsonPath('data.skor_home', 0);

        $this->patchJson(route('admin.matches.updateScore', $match), [
            'side' => 'home',
            'action' => 'decrement',
        ])
            ->assertOk()
            ->assertJsonPath('data.skor_home', 0);
    }

    public function test_admin_can_delete_a_match(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.matches.destroy', $match))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('matches', ['id' => $match->id]);
    }

    public function test_non_admin_cannot_delete_a_match(): void
    {
        $participant = User::factory()->create();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($participant)
            ->delete(route('admin.matches.destroy', $match))
            ->assertForbidden();

        $this->assertDatabaseHas('matches', ['id' => $match->id]);
    }

    public function test_admin_can_record_goal_for_player_and_increment_score(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $homePlayer = Player::create([
            'team_id' => $home->id,
            'nama_lengkap' => 'Budi Santoso',
            'nomor_punggung' => 10,
            'posisi' => 'Pivot',
        ]);
        $awayPlayer = Player::create([
            'team_id' => $away->id,
            'nama_lengkap' => 'Andi Wijaya',
            'nomor_punggung' => 7,
            'posisi' => 'Flank',
        ]);
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
            'status_match' => 'live',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.matches.recordGoal', $match), [
                'team_side' => 'home',
                'player_id' => $homePlayer->id,
                'increment_score' => 1,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('players', [
            'id' => $homePlayer->id,
            'jumlah_gol' => 1,
        ]);
        $this->assertDatabaseHas('matches', [
            'id' => $match->id,
            'skor_home' => 1,
            'skor_away' => 0,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.matches.recordGoal', $match), [
                'team_side' => 'away',
                'player_id' => $awayPlayer->id,
                'increment_score' => 0,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.jumlah_gol', 1)
            ->assertJsonPath('data.skor_away', 0);
    }

    public function test_admin_cannot_record_goal_for_player_not_in_selected_team_side(): void
    {
        $admin = $this->createAdmin();
        $home = $this->createTeam('Home Team');
        $away = $this->createTeam('Away Team');
        $awayPlayer = Player::create([
            'team_id' => $away->id,
            'nama_lengkap' => 'Pemain Away',
            'nomor_punggung' => 8,
            'posisi' => 'Flank',
        ]);
        $match = MatchModel::create([
            'team_home_id' => $home->id,
            'team_away_id' => $away->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan Utama',
            'babak' => 'Penyisihan',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.matches.index'))
            ->post(route('admin.matches.recordGoal', $match), [
                'team_side' => 'home',
                'player_id' => $awayPlayer->id,
            ])
            ->assertSessionHasErrors('player_id');

        $this->assertDatabaseHas('players', [
            'id' => $awayPlayer->id,
            'jumlah_gol' => 0,
        ]);
    }

    private function createAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }

    private function createTeam(string $name, string $kategori = 'putra'): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'kategori' => $kategori,
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'approved',
        ]);
    }
}
