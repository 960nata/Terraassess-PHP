<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ReportService;
use App\Models\User;
use App\Models\UserTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = new ReportService();
    }

    /** @test */
    public function it_can_generate_transkrip_for_student()
    {
        $student = User::factory()->create(['roles_id' => 4]);
        $tugas = Tugas::factory()->create();
        
        UserTugas::factory()->create([
            'user_id' => $student->id,
            'tugas_id' => $tugas->id,
            'nilai' => 85,
            'status' => 'Telah dinilai'
        ]);

        $pdf = $this->reportService->generateTranskrip($student->id);

        $this->assertInstanceOf(\Barryvdh\DomPDF\PDF::class, $pdf);
    }

    /** @test */
    public function it_can_generate_class_report()
    {
        $kelas = Kelas::factory()->create();
        $student1 = User::factory()->create(['kelas_id' => $kelas->id]);
        $student2 = User::factory()->create(['kelas_id' => $kelas->id]);
        $tugas = Tugas::factory()->create();
        
        UserTugas::factory()->create([
            'user_id' => $student1->id,
            'tugas_id' => $tugas->id,
            'nilai' => 80
        ]);
        
        UserTugas::factory()->create([
            'user_id' => $student2->id,
            'tugas_id' => $tugas->id,
            'nilai' => 90
        ]);

        $pdf = $this->reportService->generateClassReport($kelas->id);

        $this->assertInstanceOf(\Barryvdh\DomPDF\PDF::class, $pdf);
    }

    /** @test */
    public function it_can_generate_teacher_report()
    {
        $teacher = User::factory()->create(['roles_id' => 3]);
        $student = User::factory()->create();
        $tugas = Tugas::factory()->create();
        
        UserTugas::factory()->create([
            'user_id' => $student->id,
            'tugas_id' => $tugas->id,
            'nilai' => 85,
            'dinilai_oleh' => $teacher->id
        ]);

        $pdf = $this->reportService->generateTeacherReport($teacher->id);

        $this->assertInstanceOf(\Barryvdh\DomPDF\PDF::class, $pdf);
    }

    /** @test */
    public function it_can_generate_filename()
    {
        $filename = $this->reportService->generateFilename('transkrip', '123', 'pdf');
        
        $this->assertStringContains('transkrip_123_', $filename);
        $this->assertStringEndsWith('.pdf', $filename);
    }

    /** @test */
    public function it_calculates_student_stats_correctly()
    {
        $student = User::factory()->create();
        $tugas1 = Tugas::factory()->create();
        $tugas2 = Tugas::factory()->create();
        
        UserTugas::factory()->create([
            'user_id' => $student->id,
            'tugas_id' => $tugas1->id,
            'nilai' => 80
        ]);
        
        UserTugas::factory()->create([
            'user_id' => $student->id,
            'tugas_id' => $tugas2->id,
            'nilai' => 90
        ]);

        $nilai = UserTugas::where('user_id', $student->id)->get();
        $stats = $this->invokePrivateMethod($this->reportService, 'calculateStudentStats', [$nilai]);

        $this->assertEquals(2, $stats['total_tugas']);
        $this->assertEquals(85, $stats['avg_nilai']);
        $this->assertEquals(90, $stats['highest_nilai']);
        $this->assertEquals(80, $stats['lowest_nilai']);
    }

    private function invokePrivateMethod($object, $methodName, $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
