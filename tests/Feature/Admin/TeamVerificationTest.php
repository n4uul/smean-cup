<?php

namespace Tests\Feature\Admin;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_redirects_to_team_verification(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.teams.index'));
    }

    public function test_participant_dashboard_displays_team_registration(): void
    {
        $participant = User::factory()->create();

        $this->actingAs($participant)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pendaftaran Tim')
            ->assertSee('Kirim Pendaftaran');
    }

    public function test_participant_dashboard_displays_team_verification_status(): void
    {
        $participant = User::factory()->create();
        Team::create([
            'user_id' => $participant->id,
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'approved',
        ]);

        $this->actingAs($participant)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Garut United')
            ->assertSee('Terkonfirmasi & Terverifikasi')
            ->assertSee('SMA Garut');
    }

    public function test_admin_can_view_registered_teams(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $team = Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.teams.index'))
            ->assertOk()
            ->assertSee($team->nama_tim)
            ->assertSee($team->user->email)
            ->assertSee('Setujui')
            ->assertSee('Tolak');
    }

    public function test_non_admin_cannot_access_team_verification(): void
    {
        $participant = User::factory()->create();

        $this->actingAs($participant)
            ->get(route('admin.teams.index'))
            ->assertForbidden();
    }

    public function test_admin_can_update_a_team_verification_status(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $team = Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.teams.updateStatus', $team), [
                'status_verifikasi' => 'approved',
            ])
            ->assertRedirect();

        $this->assertSame('approved', $team->fresh()->status_verifikasi);
    }
}
