<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TugasService;
use App\Models\Tugas;
use App\Models\User;
use App\Models\KelasMapel;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TugasServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $tugasService;
    protected $user;
    protected $kelasMapel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tugasService = new TugasService();
        
        // Create test data
        $role = Role::create(['name' => 'Pengajar']);
        $this->user = User::factory()->create(['roles_id' => $role->id]);
        $this->kelasMapel = KelasMapel::factory()->create();
        
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_tugas()
    {
        $data = [
            'judul' => 'Test Tugas',
            'deskripsi' => 'Deskripsi tugas test',
            'deadline' => now()->addDays(7),
            'tipe_tugas' => 'individual',
            'kelas_mapel_id' => $this->kelasMapel->id,
        ];

        $tugas = $this->tugasService->createTugas($data);

        $this->assertInstanceOf(Tugas::class, $tugas);
        $this->assertEquals('Test Tugas', $tugas->judul);
        $this->assertEquals($this->user->id, $tugas->user_id);
        $this->assertDatabaseHas('tugas', [
            'judul' => 'Test Tugas',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_create_tugas_with_files()
    {
        Storage::fake('public');
        
        $data = [
            'judul' => 'Test Tugas dengan File',
            'deskripsi' => 'Deskripsi tugas test',
            'deadline' => now()->addDays(7),
            'tipe_tugas' => 'individual',
            'kelas_mapel_id' => $this->kelasMapel->id,
        ];

        $file = UploadedFile::fake()->create('test.pdf', 100);

        $tugas = $this->tugasService->createTugas($data, [$file]);

        $this->assertInstanceOf(Tugas::class, $tugas);
        $this->assertCount(1, $tugas->tugasFiles);
        Storage::disk('public')->assertExists('tugas/' . $tugas->tugasFiles->first()->nama_file);
    }

    /** @test */
    public function it_can_update_tugas()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $data = [
            'judul' => 'Updated Tugas',
            'deskripsi' => 'Updated deskripsi',
            'deadline' => now()->addDays(10),
            'tipe_tugas' => 'kelompok',
        ];

        $updatedTugas = $this->tugasService->updateTugas($tugas, $data);

        $this->assertEquals('Updated Tugas', $updatedTugas->judul);
        $this->assertEquals('Updated deskripsi', $updatedTugas->deskripsi);
        $this->assertEquals('kelompok', $updatedTugas->tipe_tugas);
    }

    /** @test */
    public function it_can_get_tugas_with_relations()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $tugasWithRelations = $this->tugasService->getTugasWithRelations($tugas->id);

        $this->assertTrue($tugasWithRelations->relationLoaded('kelasMapel'));
        $this->assertTrue($tugasWithRelations->relationLoaded('tugasFiles'));
        $this->assertTrue($tugasWithRelations->relationLoaded('user'));
    }

    /** @test */
    public function it_can_check_user_access_to_tugas()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        // Test pengajar access
        $hasAccess = $this->tugasService->hasAccessToTugas($tugas->id, $this->user->id, 2);
        $this->assertTrue($hasAccess);

        // Test different user access
        $otherUser = User::factory()->create();
        $hasAccess = $this->tugasService->hasAccessToTugas($tugas->id, $otherUser->id, 2);
        $this->assertFalse($hasAccess);
    }

    /** @test */
    public function it_can_delete_tugas()
    {
        Storage::fake('public');
        
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        // Create a file for the tugas
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $this->tugasService->createTugas([
            'judul' => 'Test Tugas',
            'deskripsi' => 'Deskripsi',
            'deadline' => now()->addDays(7),
            'tipe_tugas' => 'individual',
            'kelas_mapel_id' => $this->kelasMapel->id,
        ], [$file]);

        $this->tugasService->deleteTugas($tugas);

        $this->assertDatabaseMissing('tugas', ['id' => $tugas->id]);
    }

    /** @test */
    public function it_returns_false_for_invalid_access_check()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $hasAccess = $this->tugasService->hasAccessToTugas($tugas->id, $this->user->id, 99); // Invalid role
        $this->assertFalse($hasAccess);
    }
}
