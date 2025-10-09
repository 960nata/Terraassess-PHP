<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Tugas;
use App\Models\KelasMapel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TugasApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kelasMapel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['name' => 'Pengajar']);
        $this->user = User::factory()->create(['roles_id' => $role->id]);
        $this->kelasMapel = KelasMapel::factory()->create();
        
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_get_tugas_list()
    {
        Tugas::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->getJson('/api/tugas');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'judul',
                                'deskripsi',
                                'deadline',
                                'tipe_tugas',
                                'created_at',
                                'updated_at',
                            ]
                        ]
                    ],
                    'message'
                ])
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_create_tugas()
    {
        $data = [
            'judul' => 'Test Tugas API',
            'deskripsi' => 'Deskripsi tugas test',
            'deadline' => now()->addDays(7)->toISOString(),
            'tipe_tugas' => 'individual',
            'kelas_mapel_id' => $this->kelasMapel->id,
        ];

        $response = $this->postJson('/api/tugas', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'judul',
                        'deskripsi',
                        'deadline',
                        'tipe_tugas',
                        'kelas_mapel',
                        'tugas_files',
                        'user'
                    ],
                    'message'
                ])
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('tugas', [
            'judul' => 'Test Tugas API',
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
            'deadline' => now()->addDays(7)->toISOString(),
            'tipe_tugas' => 'individual',
            'kelas_mapel_id' => $this->kelasMapel->id,
        ];

        $file = UploadedFile::fake()->create('test.pdf', 100);

        $response = $this->postJson('/api/tugas', array_merge($data, [
            'file_tugas' => [$file]
        ]));

        $response->assertStatus(201)
                ->assertJson(['success' => true]);

        $tugas = Tugas::where('judul', 'Test Tugas dengan File')->first();
        $this->assertCount(1, $tugas->tugasFiles);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/tugas', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'judul',
                    'deskripsi',
                    'deadline',
                    'tipe_tugas',
                    'kelas_mapel_id'
                ]);
    }

    /** @test */
    public function it_can_show_tugas()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->getJson("/api/tugas/{$tugas->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'judul',
                        'deskripsi',
                        'deadline',
                        'tipe_tugas',
                        'kelas_mapel',
                        'tugas_files',
                        'user'
                    ],
                    'message'
                ])
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_update_tugas()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $data = [
            'judul' => 'Updated Tugas API',
            'deskripsi' => 'Updated deskripsi',
            'deadline' => now()->addDays(10)->toISOString(),
            'tipe_tugas' => 'kelompok',
        ];

        $response = $this->putJson("/api/tugas/{$tugas->id}", $data);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('tugas', [
            'id' => $tugas->id,
            'judul' => 'Updated Tugas API',
        ]);
    }

    /** @test */
    public function it_can_delete_tugas()
    {
        $tugas = Tugas::factory()->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->deleteJson("/api/tugas/{$tugas->id}");

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('tugas', ['id' => $tugas->id]);
    }

    /** @test */
    public function it_can_get_tugas_statistics()
    {
        Tugas::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->getJson('/api/tugas/statistics');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'total',
                        'this_week',
                        'this_month',
                        'by_type'
                    ],
                    'message'
                ])
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_search_tugas()
    {
        Tugas::factory()->create([
            'judul' => 'Matematika Dasar',
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        Tugas::factory()->create([
            'judul' => 'Fisika Lanjutan',
            'user_id' => $this->user->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->getJson('/api/tugas/search?q=matematika');

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(1, 'data.data');
    }

    /** @test */
    public function it_returns_404_for_nonexistent_tugas()
    {
        $response = $this->getJson('/api/tugas/999');

        $response->assertStatus(404)
                ->assertJson(['success' => false]);
    }

    /** @test */
    public function it_denies_access_to_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $tugas = Tugas::factory()->create([
            'user_id' => $otherUser->id,
            'kelas_mapel_id' => $this->kelasMapel->id,
        ]);

        $response = $this->getJson("/api/tugas/{$tugas->id}");

        $response->assertStatus(403)
                ->assertJson(['success' => false]);
    }
}
