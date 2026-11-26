<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamRegistrationQuotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_teams_receive_sequential_letter_suffixes_after_the_first_registration(): void
    {
        $this->registerTeam('Garut United', 'MTsN 1 Garut')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut United',
            'asal_sekolah' => 'MTsN 1 Garut',
        ]);

        $this->registerTeam('Garut United', 'mtsn 1 GARUT')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut United A',
            'asal_sekolah' => 'MTsN 1 Garut',
        ]);
        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut United B',
            'asal_sekolah' => 'mtsn 1 GARUT',
        ]);

        $this->registerTeam('Garut United', '  MTSN 1 garut  ')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut United C',
            'asal_sekolah' => 'MTSN 1 garut',
        ]);
    }

    public function test_one_submission_creates_requested_teams_with_shared_school_information(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('teams.store'), [
                'nama_tim' => 'SMPN 1 Garut',
                'kategori' => 'putra',
                'jumlah_tim' => 3,
                'asal_sekolah' => 'SMPN 1 Garut',
                'nama_pelatih' => 'Official Sekolah',
                'no_wa' => '08123456789',
                'berkas_syarat' => UploadedFile::fake()->create('syarat.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $teams = $user->fresh()->teams()->orderBy('id')->get();

        $this->assertSame(
            ['SMPN 1 Garut A', 'SMPN 1 Garut B', 'SMPN 1 Garut C'],
            $teams->pluck('nama_tim')->all()
        );
        $this->assertSame(['SMPN 1 Garut', 'SMPN 1 Garut', 'SMPN 1 Garut'], $teams->pluck('asal_sekolah')->all());
        $this->assertSame(['Official Sekolah', 'Official Sekolah', 'Official Sekolah'], $teams->pluck('nama_pelatih')->all());
        $this->assertSame(1, $teams->pluck('berkas_syarat')->unique()->count());
        Storage::disk('public')->assertExists($teams->first()->berkas_syarat);

        $teams[1]->update(['status_verifikasi' => 'approved']);
        $teams[2]->update(['status_verifikasi' => 'rejected']);
        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('SMPN 1 Garut A')
            ->assertSee('SMPN 1 Garut B')
            ->assertSee('SMPN 1 Garut C')
            ->assertSee('Menunggu Verifikasi')
            ->assertSee('Terkonfirmasi & Terverifikasi')
            ->assertSee('Ditolak / Perlu Revisi');
    }

    public function test_batch_cannot_exceed_the_remaining_school_quota(): void
    {
        foreach (range(1, 4) as $number) {
            $this->createTeam("Existing Team {$number}", 'MTsN 1 Garut', 'pending');
        }

        $user = User::factory()->create();
        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('teams.store'), [
                'nama_tim' => 'Garut United',
                'kategori' => 'putra',
                'jumlah_tim' => 2,
                'asal_sekolah' => 'MTsN 1 Garut',
                'nama_pelatih' => 'Pelatih',
                'no_wa' => '08123456789',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('jumlah_tim');

        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseMissing('teams', ['user_id' => $user->id]);
    }

    public function test_account_cannot_submit_another_registration_after_its_first_batch(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('teams.store'), [
            'nama_tim' => 'Garut United',
            'kategori' => 'putra',
            'jumlah_tim' => 2,
            'asal_sekolah' => 'SMA Garut',
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
        ])->assertSessionHasNoErrors();

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('teams.store'), [
                'nama_tim' => 'Garut United',
                'kategori' => 'putra',
                'jumlah_tim' => 1,
                'asal_sekolah' => 'SMA Garut',
                'nama_pelatih' => 'Pelatih',
                'no_wa' => '08123456789',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('nama_tim');

        $this->assertDatabaseCount('teams', 2);
    }

    public function test_rejected_teams_do_not_use_school_registration_quota(): void
    {
        $this->createTeam('Team One', 'MTsN 1 Garut', 'pending');
        $this->createTeam('Team Two', 'mTsN 1 GARUT', 'approved');
        $this->createTeam('Team Three', 'MTSN 1 garut', 'pending');
        $this->createTeam('Team Four', ' MTsN 1 Garut ', 'approved');
        $this->createTeam('Rejected One', 'MTsN 1 Garut', 'rejected');
        $this->createTeam('Rejected Two', 'mtsn 1 garut', 'rejected');

        $this->registerTeam('Fifth Team', 'mtsn 1 garut')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Fifth Team E',
            'asal_sekolah' => 'mtsn 1 garut',
            'status_verifikasi' => 'pending',
        ]);
    }

    public function test_registration_is_rejected_at_five_active_teams_and_displays_quota_alert(): void
    {
        foreach (range(1, 5) as $number) {
            $this->createTeam("Existing Team {$number}", 'MTsN 1 Garut', $number % 2 ? 'pending' : 'approved');
        }
        $this->createTeam('Rejected Team', 'mtsn 1 garut', 'rejected');

        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->from(route('dashboard'))
            ->followingRedirects()
            ->post(route('teams.store'), [
                'nama_tim' => 'New Team',
                'kategori' => 'putra',
                'jumlah_tim' => 1,
                'asal_sekolah' => '  mTsN 1 gARUT ',
                'nama_pelatih' => 'Pelatih',
                'no_wa' => '08123456789',
            ]);

        $response->assertOk()
            ->assertSee('Kuota pendaftaran untuk sekolah ini sudah penuh (Maksimal 5 tim per sekolah).')
            ->assertSee('role="alert"', false);

        $this->assertDatabaseCount('teams', 6);
        $this->assertDatabaseMissing('teams', ['user_id' => $user->id]);
    }

    public function test_registration_stores_selected_kategori_for_all_registered_teams(): void
    {
        $this->registerTeam('Garut Putri', 'SMPN 2 Garut', 2, 'putri')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut Putri A',
            'kategori' => 'putri',
        ]);
        $this->assertDatabaseHas('teams', [
            'nama_tim' => 'Garut Putri B',
            'kategori' => 'putri',
        ]);
    }

    public function test_registration_rejects_invalid_kategori(): void
    {
        $this->registerTeam('Invalid Team', 'SMPN 3 Garut', 1, 'campuran')
            ->assertSessionHasErrors('kategori');

        $this->assertDatabaseMissing('teams', [
            'nama_tim' => 'Invalid Team',
        ]);
    }

    private function registerTeam(string $name, string $school, int $count = 1, string $kategori = 'putra')
    {
        $user = User::factory()->create();

        return $this->actingAs($user)->post(route('teams.store'), [
            'nama_tim' => $name,
            'kategori' => $kategori,
            'jumlah_tim' => $count,
            'asal_sekolah' => $school,
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
        ]);
    }

    private function createTeam(string $name, string $school, string $status): Team
    {
        return Team::create([
            'user_id' => User::factory()->create()->id,
            'nama_tim' => $name,
            'asal_sekolah' => $school,
            'nama_pelatih' => 'Pelatih',
            'no_wa' => '08123456789',
            'status_verifikasi' => $status,
        ]);
    }
}
