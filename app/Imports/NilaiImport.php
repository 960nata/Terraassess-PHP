<?php

namespace App\Imports;

use App\Models\UserTugas;
use App\Models\User;
use App\Models\Tugas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NilaiImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $tugasId;
    protected $errors = [];
    protected $successCount = 0;

    public function __construct($tugasId)
    {
        $this->tugasId = $tugasId;
    }

    public function model(array $row)
    {
        try {
            // Find student by email or NIS
            $student = User::where('email', $row['email'])
                ->orWhere('nis', $row['nis'] ?? null)
                ->first();

            if (!$student) {
                $this->errors[] = "Siswa dengan email {$row['email']} tidak ditemukan";
                return null;
            }

            // Check if tugas exists
            $tugas = Tugas::find($this->tugasId);
            if (!$tugas) {
                $this->errors[] = "Tugas dengan ID {$this->tugasId} tidak ditemukan";
                return null;
            }

            // Validate nilai
            $nilai = (int) $row['nilai'];
            if ($nilai < 0 || $nilai > 100) {
                $this->errors[] = "Nilai untuk {$student->name} harus antara 0-100";
                return null;
            }

            // Check if user task already exists
            $existingUserTugas = UserTugas::where('user_id', $student->id)
                ->where('tugas_id', $this->tugasId)
                ->first();

            if ($existingUserTugas) {
                // Update existing record
                $existingUserTugas->update([
                    'nilai' => $nilai,
                    'komentar' => $row['komentar'] ?? $existingUserTugas->komentar,
                    'dinilai_oleh' => Auth::id(),
                    'dinilai_pada' => now(),
                    'revisi_ke' => $existingUserTugas->revisi_ke + 1,
                    'status' => 'Telah dinilai'
                ]);

                $this->successCount++;
                return null; // Don't create new model since we updated existing
            } else {
                // Create new record
                $this->successCount++;
                return new UserTugas([
                    'user_id' => $student->id,
                    'tugas_id' => $this->tugasId,
                    'nilai' => $nilai,
                    'komentar' => $row['komentar'] ?? null,
                    'dinilai_oleh' => Auth::id(),
                    'dinilai_pada' => now(),
                    'revisi_ke' => 1,
                    'status' => 'Telah dinilai'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error importing nilai: ' . $e->getMessage());
            $this->errors[] = "Error processing row: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'nilai' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string|max:1000',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.required' => 'Email siswa harus diisi',
            'email.email' => 'Format email tidak valid',
            'nilai.required' => 'Nilai harus diisi',
            'nilai.numeric' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimum adalah 0',
            'nilai.max' => 'Nilai maksimum adalah 100',
            'komentar.max' => 'Komentar maksimal 1000 karakter',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getImportSummary()
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => count($this->errors),
            'errors' => $this->errors,
            'total_processed' => $this->successCount + count($this->errors)
        ];
    }
}
