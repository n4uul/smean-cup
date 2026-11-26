<?php

namespace Tests\Feature\Admin;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            'kategori' => 'putri',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.teams.index'))
            ->assertOk()
            ->assertSee($team->nama_tim)
            ->assertSee('Putri')
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

    public function test_admin_can_delete_a_team_and_its_associated_files_and_matches(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $logoPath = UploadedFile::fake()->create('logo.jpg', 100, 'image/jpeg')->store('logos', 'public');
        $berkasPath = UploadedFile::fake()->create('syarat.pdf', 100)->store('berkas', 'public');

        $team = Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'logo_tim' => $logoPath,
            'berkas_syarat' => $berkasPath,
            'status_verifikasi' => 'approved',
        ]);

        $opponent = Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => 'Tasik FC',
            'asal_sekolah' => 'SMA Tasik',
            'nama_pelatih' => 'Pelatih Lawan',
            'no_wa' => '08987654321',
            'status_verifikasi' => 'approved',
        ]);

        $match = MatchModel::create([
            'team_home_id' => $team->id,
            'team_away_id' => $opponent->id,
            'waktu_tanding' => '2026-12-01 10:00:00',
            'lapangan' => 'Lapangan A',
            'babak' => 'Penyisihan',
        ]);

        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($berkasPath);

        $this->actingAs($admin)
            ->delete(route('admin.teams.destroy', $team))
            ->assertRedirect(route('admin.teams.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
        $this->assertDatabaseMissing('matches', ['id' => $match->id]);
        $this->assertDatabaseHas('teams', ['id' => $opponent->id]);

        Storage::disk('public')->assertMissing($logoPath);
        Storage::disk('public')->assertMissing($berkasPath);
    }

    public function test_deleting_one_team_keeps_files_shared_with_other_teams(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $participant = User::factory()->create();
        $logoPath = 'logos/shared-school-logo.png';
        $documentPath = 'berkas/shared-school-requirements.pdf';
        Storage::disk('public')->put($logoPath, 'shared logo');
        Storage::disk('public')->put($documentPath, 'shared requirements');

        $firstTeam = Team::create([
            'user_id' => $participant->id,
            'nama_tim' => 'Garut United A',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'logo_tim' => $logoPath,
            'berkas_syarat' => $documentPath,
            'status_verifikasi' => 'pending',
        ]);
        $secondTeam = Team::create([
            'user_id' => $participant->id,
            'nama_tim' => 'Garut United B',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'logo_tim' => $logoPath,
            'berkas_syarat' => $documentPath,
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.teams.destroy', $firstTeam))
            ->assertRedirect(route('admin.teams.index'));

        $this->assertDatabaseHas('teams', ['id' => $secondTeam->id]);
        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($documentPath);
    }

    public function test_non_admin_cannot_delete_a_team(): void
    {
        $participant = User::factory()->create();

        $team = Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => 'approved',
        ]);

        $this->actingAs($participant)
            ->delete(route('admin.teams.destroy', $team))
            ->assertForbidden();

        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }
}
