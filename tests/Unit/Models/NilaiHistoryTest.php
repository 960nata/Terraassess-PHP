<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\NilaiHistory;
use App\Models\UserTugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NilaiHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_user_tugas()
    {
        $userTugas = UserTugas::factory()->create();
        $history = NilaiHistory::factory()->create(['user_tugas_id' => $userTugas->id]);

        $this->assertInstanceOf(UserTugas::class, $history->userTugas);
        $this->assertEquals($userTugas->id, $history->userTugas->id);
    }

    /** @test */
    public function it_belongs_to_pengubah()
    {
        $user = User::factory()->create();
        $history = NilaiHistory::factory()->create(['diubah_oleh' => $user->id]);

        $this->assertInstanceOf(User::class, $history->pengubah);
        $this->assertEquals($user->id, $history->pengubah->id);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $userTugas = UserTugas::factory()->create();
        $user = User::factory()->create();
        
        $data = [
            'user_tugas_id' => $userTugas->id,
            'nilai_lama' => 70,
            'nilai_baru' => 85,
            'komentar_lama' => 'Perlu perbaikan',
            'komentar_baru' => 'Sudah lebih baik',
            'diubah_oleh' => $user->id,
            'alasan_revisi' => 'Koreksi penilaian',
            'diubah_pada' => now()
        ];

        $history = NilaiHistory::create($data);

        $this->assertDatabaseHas('nilai_history', $data);
        $this->assertEquals(70, $history->nilai_lama);
        $this->assertEquals(85, $history->nilai_baru);
    }

    /** @test */
    public function it_casts_diubah_pada_to_datetime()
    {
        $history = NilaiHistory::factory()->create();
        
        $this->assertInstanceOf(\Carbon\Carbon::class, $history->diubah_pada);
    }
}
