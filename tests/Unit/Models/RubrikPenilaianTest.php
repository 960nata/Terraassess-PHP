<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\RubrikPenilaian;
use App\Models\Tugas;
use App\Models\UserTugasRubrik;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RubrikPenilaianTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_tugas()
    {
        $tugas = Tugas::factory()->create();
        $rubrik = RubrikPenilaian::factory()->create(['tugas_id' => $tugas->id]);

        $this->assertInstanceOf(Tugas::class, $rubrik->tugas);
        $this->assertEquals($tugas->id, $rubrik->tugas->id);
    }

    /** @test */
    public function it_has_many_user_tugas_rubrik()
    {
        $rubrik = RubrikPenilaian::factory()->create();
        $userTugasRubrik = UserTugasRubrik::factory()->create(['rubrik_id' => $rubrik->id]);

        $this->assertTrue($rubrik->userTugasRubrik->contains($userTugasRubrik));
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $tugas = Tugas::factory()->create();
        $rubrikData = [
            'tugas_id' => $tugas->id,
            'aspek' => 'Isi & Analisis',
            'bobot' => 40,
            'deskripsi' => 'Kedalaman analisis dan relevansi isi'
        ];

        $rubrik = RubrikPenilaian::create($rubrikData);

        $this->assertDatabaseHas('rubrik_penilaian', $rubrikData);
        $this->assertEquals('Isi & Analisis', $rubrik->aspek);
        $this->assertEquals(40, $rubrik->bobot);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        RubrikPenilaian::create([
            'aspek' => 'Test Aspek',
            // Missing required fields
        ]);
    }
}
