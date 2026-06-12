<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiakadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_admin_can_access_dosen_management(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/dosen');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Dosen');
    }

    public function test_student_cannot_access_dosen_management(): void
    {
        $studentUser = User::where('role', 'mahasiswa')->first();

        $response = $this->actingAs($studentUser)->get('/dosen');

        $response->assertStatus(403);
    }

    public function test_student_can_take_krs_under_limit(): void
    {
        $studentUser = User::where('role', 'mahasiswa')->first();
        
        $taken = Krs::where('npm', $studentUser->npm)->pluck('kode_matakuliah')->toArray();
        $available = MataKuliah::whereNotIn('kode_matakuliah', $taken)->first();

        $response = $this->actingAs($studentUser)->post('/krs', [
            'kode_matakuliah' => $available->kode_matakuliah
        ]);

        $response->assertRedirect('/krs');
        $this->assertDatabaseHas('krs', [
            'npm' => $studentUser->npm,
            'kode_matakuliah' => $available->kode_matakuliah
        ]);
    }
}
