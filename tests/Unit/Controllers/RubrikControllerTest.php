<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\RubrikController;
use App\Models\RubrikPenilaian;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class RubrikControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $tugas;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->teacher = User::factory()->create(['roles_id' => 3]); // Teacher
        $this->tugas = Tugas::factory()->create();
        
        $this->actingAs($this->teacher);
    }

    /** @test */
    public function it_can_store_rubrik_with_valid_data()
    {
        $request = Request::create('/rubrik/store', 'POST', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Isi & Analisis', 'Struktur & Organisasi'],
            'bobot' => [60, 40],
            'deskripsi' => ['Kedalaman analisis', 'Keruntutan penyajian']
        ]);

        $controller = new RubrikController();
        $response = $controller->store($request);

        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Isi & Analisis',
            'bobot' => 60
        ]);

        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Struktur & Organisasi',
            'bobot' => 40
        ]);
    }

    /** @test */
    public function it_rejects_rubrik_with_invalid_total_bobot()
    {
        $request = Request::create('/rubrik/store', 'POST', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Isi & Analisis', 'Struktur & Organisasi'],
            'bobot' => [60, 50], // Total = 110, should be 100
            'deskripsi' => ['Kedalaman analisis', 'Keruntutan penyajian']
        ]);

        $controller = new RubrikController();
        $response = $controller->store($request);

        $this->assertDatabaseMissing('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id
        ]);
    }

    /** @test */
    public function it_can_update_rubrik()
    {
        $rubrik = RubrikPenilaian::factory()->create([
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Test Aspek',
            'bobot' => 50
        ]);

        $request = Request::create("/rubrik/{$rubrik->id}", 'PUT', [
            'aspek' => 'Updated Aspek',
            'bobot' => 60,
            'deskripsi' => 'Updated description'
        ]);

        $controller = new RubrikController();
        $response = $controller->update($request, $rubrik->id);

        $this->assertDatabaseHas('rubrik_penilaian', [
            'id' => $rubrik->id,
            'aspek' => 'Updated Aspek',
            'bobot' => 60
        ]);
    }

    /** @test */
    public function it_can_delete_rubrik()
    {
        $rubrik = RubrikPenilaian::factory()->create([
            'tugas_id' => $this->tugas->id
        ]);

        $controller = new RubrikController();
        $response = $controller->destroy($rubrik->id);

        $this->assertDatabaseMissing('rubrik_penilaian', [
            'id' => $rubrik->id
        ]);
    }

    /** @test */
    public function it_denies_access_to_unauthorized_users()
    {
        $student = User::factory()->create(['roles_id' => 4]); // Student
        $this->actingAs($student);

        $request = Request::create('/rubrik/store', 'POST', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Test'],
            'bobot' => [100]
        ]);

        $controller = new RubrikController();
        
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $controller->store($request);
    }
}
