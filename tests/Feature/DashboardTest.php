<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard')
            ->has('bankGroups')
            ->where('bankGroups.Bank Umum Milik Negara (BUMN / Himbara).0', 'Bank Mandiri (PT Bank Mandiri (Persero) Tbk)'));
    }

    public function test_registered_bank_is_stored_on_claim(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('pengajuan-klaim.store'), $this->claimData([
            'bank_choice' => 'Bank Jago (PT Bank Jago Tbk)',
        ]));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('pengajuan_klaim', ['nama_bank' => 'Bank Jago (PT Bank Jago Tbk)']);
    }

    public function test_other_bank_stores_trimmed_custom_name(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('pengajuan-klaim.store'), $this->claimData([
            'bank_choice' => '__other__',
            'nama_bank_lainnya' => '  Bank Contoh  ',
        ]));

        $this->assertDatabaseHas('pengajuan_klaim', ['nama_bank' => 'Bank Contoh']);
    }

    public function test_other_bank_requires_custom_name_and_rejects_unknown_choice(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('pengajuan-klaim.store'), $this->claimData([
            'bank_choice' => '__other__',
        ]))->assertSessionHasErrors('nama_bank_lainnya');

        $this->actingAs(User::factory()->create())->post(route('pengajuan-klaim.store'), $this->claimData([
            'bank_choice' => 'Bank Palsu',
        ]))->assertSessionHasErrors('bank_choice');
    }

    private function claimData(array $overrides = []): array
    {
        return array_merge([
            'nama_ahli_waris' => 'Ahli Waris',
            'no_hp_peserta' => '081234567890',
            'no_hp_ahli_waris' => '081298765432',
            'nik_peserta' => '1234567890123456',
            'akta_kematian' => UploadedFile::fake()->create('akta.pdf', 10, 'application/pdf'),
            'kartu_bpjs' => UploadedFile::fake()->create('bpjs.pdf', 10, 'application/pdf'),
            'buku_rekening' => UploadedFile::fake()->create('rekening.pdf', 10, 'application/pdf'),
            'keterangan_ahli_waris' => 'Anak kandung peserta.',
            'nomor_rekening_ahli_waris' => '1234567890',
            'bank_choice' => 'Bank Jago (PT Bank Jago Tbk)',
        ], $overrides);
    }
}
