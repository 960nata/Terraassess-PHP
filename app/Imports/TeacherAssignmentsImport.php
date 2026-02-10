<?php

namespace App\Imports;

use App\Models\EditorAccess;
use App\Models\KelasMapel;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherAssignmentsImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                DB::beginTransaction();

                // Validate required fields
                if (empty($row['nama_guru']) || empty($row['nama_kelas']) || empty($row['mata_pelajaran'])) {
                    $this->errorCount++;
                    $this->errors[] = "Row " . ($row->getIndex() + 2) . ": Missing required fields";
                    continue;
                }

                // Find or create class
                $kelas = Kelas::where('name', $row['nama_kelas'])->first();
                if (!$kelas) {
                    $kelas = Kelas::create([
                        'name' => $row['nama_kelas'],
                        'level' => $row['tingkat_kelas'] ?? 'SMA',
                        'description' => 'Imported class',
                        'max_students' => 30
                    ]);
                }

                // Find or create subject
                $mapel = Mapel::where('name', $row['mata_pelajaran'])->first();
                if (!$mapel) {
                    $mapel = Mapel::create([
                        'name' => $row['mata_pelajaran'],
                        'code' => $row['kode_mata_pelajaran'] ?? strtoupper(substr($row['mata_pelajaran'], 0, 3)),
                        'description' => 'Imported subject'
                    ]);
                }

                // Find teacher
                $teacher = User::where('name', $row['nama_guru'])
                    ->where('roles_id', 2)
                    ->first();

                if (!$teacher) {
                    // Create teacher if not exists
                    $teacher = User::create([
                        'name' => $row['nama_guru'],
                        'email' => $row['email_guru'] ?? strtolower(str_replace(' ', '.', $row['nama_guru'])) . '@school.com',
                        'password' => bcrypt('password123'),
                        'roles_id' => 2
                    ]);
                }

                // Find or create KelasMapel
                $kelasMapel = KelasMapel::where('kelas_id', $kelas->id)
                    ->where('mapel_id', $mapel->id)
                    ->first();

                if (!$kelasMapel) {
                    $kelasMapel = KelasMapel::create([
                        'kelas_id' => $kelas->id,
                        'mapel_id' => $mapel->id
                    ]);
                }

                // Check if assignment already exists
                $existingAssignment = EditorAccess::where('user_id', $teacher->id)
                    ->where('kelas_mapel_id', $kelasMapel->id)
                    ->first();

                if (!$existingAssignment) {
                    EditorAccess::create([
                        'user_id' => $teacher->id,
                        'kelas_mapel_id' => $kelasMapel->id
                    ]);
                    $this->successCount++;
                } else {
                    $this->errorCount++;
                    $this->errors[] = "Row " . ($row->getIndex() + 2) . ": Assignment already exists";
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->errorCount++;
                $this->errors[] = "Row " . ($row->getIndex() + 2) . ": " . $e->getMessage();
                Log::error('Import assignment error: ' . $e->getMessage());
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_guru' => 'required|string|max:255',
            '*.nama_kelas' => 'required|string|max:255',
            '*.mata_pelajaran' => 'required|string|max:255',
            '*.email_guru' => 'nullable|email|max:255',
            '*.tingkat_kelas' => 'nullable|string|max:50',
            '*.kode_mata_pelajaran' => 'nullable|string|max:10',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.nama_guru.required' => 'Nama guru harus diisi',
            '*.nama_kelas.required' => 'Nama kelas harus diisi',
            '*.mata_pelajaran.required' => 'Mata pelajaran harus diisi',
            '*.email_guru.email' => 'Email guru harus valid',
        ];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Get import results
     */
    public function getResults()
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'errors' => $this->errors
        ];
    }
}
