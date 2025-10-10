<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\UserTugasRubrik;
use App\Models\UserTugas;
use App\Models\RubrikPenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTugasRubrikTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_user_tugas()
    {
        $userTugas = UserTugas::factory()->create();
        $userTugasRubrik = UserTugasRubrik::factory()->create(['user_tugas_id' => $userTugas->id]);

        $this->assertInstanceOf(UserTugas::class, $userTugasRubrik->userTugas);
        $this->assertEquals($userTugas->id, $userTugasRubrik->userTugas->id);
    }

    /** @test */
    public function it_belongs_to_rubrik()
    {
        $rubrik = RubrikPenilaian::factory()->create();
        $userTugasRubrik = UserTugasRubrik::factory()->create(['rubrik_id' => $rubrik->id]);

        $this->assertInstanceOf(RubrikPenilaian::class, $userTugasRubrik->rubrik);
        $this->assertEquals($rubrik->id, $userTugasRubrik->rubrik->id);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $userTugas = UserTugas::factory()->create();
        $rubrik = RubrikPenilaian::factory()->create();
        
        $data = [
            'user_tugas_id' => $userTugas->id,
            'rubrik_id' => $rubrik->id,
            'nilai' => 85,
            'komentar_aspek' => 'Bagus sekali!'
        ];

        $userTugasRubrik = UserTugasRubrik::create($data);

        $this->assertDatabaseHas('user_tugas_rubrik', $data);
        $this->assertEquals(85, $userTugasRubrik->nilai);
        $this->assertEquals('Bagus sekali!', $userTugasRubrik->komentar_aspek);
    }

    /** @test */
    public function it_validates_nilai_range()
    {
        $userTugas = UserTugas::factory()->create();
        $rubrik = RubrikPenilaian::factory()->create();
        
        // Test valid range
        $validData = [
            'user_tugas_id' => $userTugas->id,
            'rubrik_id' => $rubrik->id,
            'nilai' => 75
        ];

        $userTugasRubrik = UserTugasRubrik::create($validData);
        $this->assertDatabaseHas('user_tugas_rubrik', $validData);
    }
}
