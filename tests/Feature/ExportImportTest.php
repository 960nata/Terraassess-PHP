<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tugas;
use App\Models\UserTugas;
use App\Exports\NilaiExport;
use App\Imports\NilaiImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

class ExportImportTest extends TestCase
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
    public function teacher_can_export_grades_to_excel()
    {
        // Create test data
        UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 85,
            'komentar' => 'Bagus sekali!',
            'status' => 'Telah dinilai'
        ]);

        Excel::fake();

        $response = $this->get(route('nilai.export', $this->tugas->id));

        Excel::assertDownloaded('nilai_tugas_' . $this->tugas->id . '.xlsx', function (NilaiExport $export) {
            return $export->collection()->count() === 1;
        });
    }

    /** @test */
    public function teacher_can_export_all_grades()
    {
        // Create test data
        UserTugas::factory()->count(3)->create([
            'nilai' => 85,
            'status' => 'Telah dinilai'
        ]);

        Excel::fake();

        $response = $this->get(route('nilai.export'));

        Excel::assertDownloaded('nilai_semua.xlsx', function (NilaiExport $export) {
            return $export->collection()->count() === 3;
        });
    }

    /** @test */
    public function teacher_can_import_grades_from_excel()
    {
        // Create test data
        $student1 = User::factory()->create(['email' => 'student1@example.com']);
        $student2 = User::factory()->create(['email' => 'student2@example.com']);

        Excel::fake();

        $file = \Illuminate\Http\UploadedFile::fake()->create('grades.xlsx', 100);

        $response = $this->post(route('nilai.import'), [
            'file' => $file,
            'tugas_id' => $this->tugas->id
        ]);

        Excel::assertImported('grades.xlsx', function (NilaiImport $import) {
            return $import->getTugasId() === $this->tugas->id;
        });
    }

    /** @test */
    public function import_validates_file_format()
    {
        $file = \Illuminate\Http\UploadedFile::fake()->create('grades.txt', 100);

        $response = $this->post(route('nilai.import'), [
            'file' => $file,
            'tugas_id' => $this->tugas->id
        ]);

        $response->assertSessionHasErrors('file');
    }

    /** @test */
    public function export_includes_correct_columns()
    {
        UserTugas::factory()->create([
            'user_id' => $this->student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 85,
            'komentar' => 'Bagus sekali!',
            'status' => 'Telah dinilai'
        ]);

        $export = new NilaiExport($this->tugas->id);
        $collection = $export->collection();

        $this->assertCount(1, $collection);
        
        $firstRow = $collection->first();
        $this->assertArrayHasKey('Nama Siswa', $firstRow);
        $this->assertArrayHasKey('Email', $firstRow);
        $this->assertArrayHasKey('Nilai', $firstRow);
        $this->assertArrayHasKey('Feedback/Komentar', $firstRow);
    }

    /** @test */
    public function export_applies_conditional_formatting()
    {
        UserTugas::factory()->create([
            'nilai' => 95 // A grade
        ]);

        UserTugas::factory()->create([
            'nilai' => 75 // C grade
        ]);

        UserTugas::factory()->create([
            'nilai' => 55 // E grade
        ]);

        $export = new NilaiExport();
        $collection = $export->collection();

        $this->assertCount(3, $collection);
        
        // Check if grades are calculated correctly
        $grades = $collection->pluck('Grade')->toArray();
        $this->assertContains('A', $grades);
        $this->assertContains('C', $grades);
        $this->assertContains('E', $grades);
    }

    /** @test */
    public function import_handles_invalid_data_gracefully()
    {
        $student = User::factory()->create(['email' => 'valid@example.com']);

        // Mock invalid data
        $invalidData = [
            'email' => 'invalid@example.com', // Non-existent user
            'nilai' => 150, // Invalid score
            'komentar' => 'Test comment'
        ];

        $import = new NilaiImport($this->tugas->id);
        
        // This should not create a record due to validation
        $result = $import->model($invalidData);
        
        $this->assertNull($result);
        $this->assertCount(1, $import->getErrors());
    }

    /** @test */
    public function import_updates_existing_records()
    {
        $student = User::factory()->create(['email' => 'student@example.com']);
        $userTugas = UserTugas::factory()->create([
            'user_id' => $student->id,
            'tugas_id' => $this->tugas->id,
            'nilai' => 70,
            'revisi_ke' => 1
        ]);

        $import = new NilaiImport($this->tugas->id);
        
        $data = [
            'email' => 'student@example.com',
            'nilai' => 85,
            'komentar' => 'Updated comment'
        ];

        $result = $import->model($data);
        
        $this->assertNull($result); // Should not create new record
        
        // Check if existing record was updated
        $userTugas->refresh();
        $this->assertEquals(85, $userTugas->nilai);
        $this->assertEquals(2, $userTugas->revisi_ke);
    }

    /** @test */
    public function unauthorized_users_cannot_export_import()
    {
        $student = User::factory()->create(['roles_id' => 4]);
        $this->actingAs($student);

        $response = $this->get(route('nilai.export'));
        $response->assertStatus(403);

        $file = \Illuminate\Http\UploadedFile::fake()->create('grades.xlsx', 100);
        $response = $this->post(route('nilai.import'), [
            'file' => $file,
            'tugas_id' => $this->tugas->id
        ]);
        $response->assertStatus(403);
    }
}
