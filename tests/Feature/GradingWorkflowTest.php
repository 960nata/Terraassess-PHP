<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tugas;
use App\Models\RubrikPenilaian;
use App\Models\UserTugas;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class GradingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $student;
    protected $tugas;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->teacher = User::factory()->create(['roles_id' => 3]);
        $this->student = User::factory()->create(['roles_id' => 4]);
        $this->tugas = Tugas::factory()->create();
        
        $this->actingAs($this->teacher);
    }

    /** @test */
    public function teacher_can_create_rubrik_for_task()
    {
        $response = $this->post('/rubrik/store', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Isi & Analisis', 'Struktur & Organisasi', 'Bahasa & Ejaan'],
            'bobot' => [40, 30, 30],
            'deskripsi' => [
                'Kedalaman analisis dan relevansi isi',
                'Keruntutan dan logika penyajian',
                'Ketepatan bahasa dan ejaan'
            ]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Isi & Analisis',
            'bobot' => 40
        ]);

        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Struktur & Organisasi',
            'bobot' => 30
        ]);

        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Bahasa & Ejaan',
            'bobot' => 30
        ]);
    }

    /** @test */
    public function teacher_can_grade_student_with_rubrik()
    {
        // Create rubrik first
        $rubrik1 = RubrikPenilaian::factory()->create([
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Isi & Analisis',
            'bobot' => 40
        ]);

        $rubrik2 = RubrikPenilaian::factory()->create([
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Struktur & Organisasi',
            'bobot' => 30
        ]);

        $rubrik3 = RubrikPenilaian::factory()->create([
            'tugas_id' => $this->tugas->id,
            'aspek' => 'Bahasa & Ejaan',
            'bobot' => 30
        ]);

        // Create user task
        $userTugas = UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'status' => 'Selesai'
        ]);

        $response = $this->post(route('siswaUpdateNilai', ['token' => encrypt($this->tugas->id)]), [
            'siswaId' => [$this->student->id],
            'nilai' => [85], // This will be calculated from rubrik
            'komentar' => ['Bagus sekali! Teruskan kerja bagusmu.'],
            'rubrik' => [
                $this->student->id => [
                    $rubrik1->id => [
                        'nilai' => 80,
                        'komentar' => 'Analisis cukup mendalam'
                    ],
                    $rubrik2->id => [
                        'nilai' => 90,
                        'komentar' => 'Struktur sangat baik'
                    ],
                    $rubrik3->id => [
                        'nilai' => 85,
                        'komentar' => 'Bahasa sudah baik'
                    ]
                ]
            ]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check if user task is updated
        $this->assertDatabaseHas('user_tugas', [
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'status' => 'Telah dinilai',
            'komentar' => 'Bagus sekali! Teruskan kerja bagusmu.'
        ]);

        // Check if rubrik scores are saved
        $this->assertDatabaseHas('user_tugas_rubrik', [
            'user_tugas_id' => $userTugas->id,
            'rubrik_id' => $rubrik1->id,
            'nilai' => 80
        ]);
    }

    /** @test */
    public function student_receives_notification_after_grading()
    {
        $userTugas = UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'status' => 'Selesai'
        ]);

        $response = $this->post(route('siswaUpdateNilai', ['token' => encrypt($this->tugas->id)]), [
            'siswaId' => [$this->student->id],
            'nilai' => [85],
            'komentar' => ['Bagus sekali!']
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->student->id,
            'title' => 'Tugas Dinilai',
            'type' => 'success',
            'related_type' => 'tugas',
            'related_id' => $this->tugas->id
        ]);
    }

    /** @test */
    public function teacher_can_view_grading_history()
    {
        $userTugas = UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 80,
            'komentar' => 'Bagus',
            'dinilai_oleh' => $this->teacher->id,
            'revisi_ke' => 1
        ]);

        // Update nilai to create history
        $userTugas->update([
            'nilai' => 85,
            'komentar' => 'Lebih bagus',
            'revisi_ke' => 2
        ]);

        $response = $this->get(route('nilai.history', $userTugas->id));

        $response->assertStatus(200);
        $response->assertViewIs('teacher.nilai-history');
        $response->assertViewHas('userTugas');
        $response->assertViewHas('history');
    }

    /** @test */
    public function student_can_view_feedback()
    {
        $this->actingAs($this->student);

        $userTugas = UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 85,
            'komentar' => 'Bagus sekali! Teruskan kerja bagusmu.',
            'status' => 'Telah dinilai',
            'dinilai_oleh' => $this->teacher->id
        ]);

        $response = $this->get(route('student.tugas.feedback', $userTugas->id));

        $response->assertStatus(200);
        $response->assertSee('Bagus sekali! Teruskan kerja bagusmu.');
        $response->assertSee('85');
    }

    /** @test */
    public function teacher_can_export_grades_to_excel()
    {
        UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 85,
            'komentar' => 'Bagus sekali!'
        ]);

        $response = $this->get(route('nilai.export', $this->tugas->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function teacher_can_view_analytics_dashboard()
    {
        // Create some test data
        UserTugas::factory()->count(5)->create([
            'nilai' => 85,
            'dinilai_oleh' => $this->teacher->id
        ]);

        $response = $this->get(route('analytics.grading'));

        $response->assertStatus(200);
        $response->assertViewIs('analytics.grading');
        $response->assertViewHas('stats');
    }

    /** @test */
    public function system_tracks_nilai_revisions()
    {
        $userTugas = UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 80,
            'komentar' => 'Bagus',
            'dinilai_oleh' => $this->teacher->id,
            'revisi_ke' => 1
        ]);

        // First revision
        $response = $this->post(route('siswaUpdateNilai', ['token' => encrypt($this->tugas->id)]), [
            'siswaId' => [$this->student->id],
            'nilai' => [85],
            'komentar' => ['Lebih bagus'],
            'alasan_revisi' => 'Koreksi penilaian'
        ]);

        $this->assertDatabaseHas('nilai_history', [
            'user_tugas_id' => $userTugas->id,
            'nilai_lama' => 80,
            'nilai_baru' => 85,
            'komentar_lama' => 'Bagus',
            'komentar_baru' => 'Lebih bagus',
            'diubah_oleh' => $this->teacher->id,
            'alasan_revisi' => 'Koreksi penilaian'
        ]);

        $this->assertDatabaseHas('user_tugas', [
            'id' => $userTugas->id,
            'revisi_ke' => 2
        ]);
    }

    /** @test */
    public function rubrik_validation_prevents_invalid_bobot()
    {
        $response = $this->post('/rubrik/store', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Aspek 1', 'Aspek 2'],
            'bobot' => [60, 50], // Total = 110, should be 100
            'deskripsi' => ['Deskripsi 1', 'Deskripsi 2']
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('rubrik_penilaian', [
            'tugas_id' => $this->tugas->id
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_access_grading_features()
    {
        $student = User::factory()->create(['roles_id' => 4]);
        $this->actingAs($student);

        $response = $this->post('/rubrik/store', [
            'tugas_id' => $this->tugas->id,
            'aspek' => ['Test'],
            'bobot' => [100]
        ]);

        $response->assertStatus(403);
    }
}
