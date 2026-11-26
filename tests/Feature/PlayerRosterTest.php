<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerRosterTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_add_and_view_players_in_team_roster(): void
    {
        $user = User::factory()->create();
        $team = $this->createTeam($user, 'SMPN 1 Garut');

        $this->actingAs($user)
            ->post(route('players.store', $team), [
                'nama_lengkap' => 'Rizky Pratama',
                'nomor_punggung' => 10,
                'posisi' => 'Pivot',
                'nisn' => '0081234567',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('players', [
            'team_id' => $team->id,
            'nama_lengkap' => 'Rizky Pratama',
            'nomor_punggung' => 10,
            'posisi' => 'Pivot',
            'nisn' => '0081234567',
            'jumlah_gol' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Kelola Roster Pemain')
            ->assertSee('Rizky Pratama')
            ->assertSee('#10')
            ->assertSee('0081234567');
    }

    public function test_jersey_number_must_be_unique_per_team_but_can_repeat_across_teams(): void
    {
        $user = User::factory()->create();
        $teamA = $this->createTeam($user, 'SMPN 1 Garut A');
        $teamB = $this->createTeam($user, 'SMPN 1 Garut B');

        Player::create([
            'team_id' => $teamA->id,
            'nama_lengkap' => 'Pemain Satu',
            'nomor_punggung' => 7,
            'posisi' => 'Flank',
        ]);

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('players.store', $teamA), [
                'nama_lengkap' => 'Pemain Dua',
                'nomor_punggung' => 7,
                'posisi' => 'Anchor',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('nomor_punggung');

        $this->actingAs($user)
            ->post(route('players.store', $teamB), [
                'nama_lengkap' => 'Pemain Tim B',
                'nomor_punggung' => 7,
                'posisi' => 'Anchor',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('players', 2);
    }

    public function test_team_roster_cannot_exceed_max_player_quota(): void
    {
        $user = User::factory()->create();
        $team = $this->createTeam($user, 'SMPN 2 Garut');

        for ($i = 1; $i <= Team::MAX_PLAYERS; $i++) {
            Player::create([
                'team_id' => $team->id,
                'nama_lengkap' => "Pemain {$i}",
                'nomor_punggung' => $i,
                'posisi' => 'Flank',
            ]);
        }

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('players.store', $team), [
                'nama_lengkap' => 'Pemain Kelebihan',
                'nomor_punggung' => 99,
                'posisi' => 'Pivot',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('nama_lengkap');

        $this->assertSame(Team::MAX_PLAYERS, $team->players()->count());
    }

    public function test_participant_can_delete_player_from_their_own_team(): void
    {
        $user = User::factory()->create();
        $team = $this->createTeam($user, 'MTsN 1 Garut');
        $player = Player::create([
            'team_id' => $team->id,
            'nama_lengkap' => 'Salah Input',
            'nomor_punggung' => 12,
            'posisi' => 'Kiper',
        ]);

        $this->actingAs($user)
            ->delete(route('players.destroy', $player))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }

    public function test_participant_cannot_manage_another_users_team_roster(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $team = $this->createTeam($owner, 'Tim Pemilik');
        $player = Player::create([
            'team_id' => $team->id,
            'nama_lengkap' => 'Pemain Asli',
            'nomor_punggung' => 5,
            'posisi' => 'Anchor',
        ]);

        $this->actingAs($otherUser)
            ->post(route('players.store', $team), [
                'nama_lengkap' => 'Penyusup',
                'nomor_punggung' => 9,
                'posisi' => 'Pivot',
            ])
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('players.destroy', $player))
            ->assertForbidden();

        $this->assertDatabaseHas('players', ['id' => $player->id]);
    }

    private function createTeam(User $user, string $name): Team
    {
        return Team::create([
            'user_id' => $user->id,
            'nama_tim' => $name,
            'kategori' => 'putra',
            'asal_sekolah' => 'SMPN 1 Garut',
            'nama_pelatih' => 'Coach Budi',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'approved',
        ]);
    }
}
