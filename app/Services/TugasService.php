<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\TugasFile;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TugasService
{
    /**
     * Create a new tugas
     */
    public function createTugas(array $data, $files = null)
    {
        return DB::transaction(function () use ($data, $files) {
            // Create tugas
            $tugas = Tugas::create([
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'deadline' => $data['deadline'],
                'tipe_tugas' => $data['tipe_tugas'],
                'kelas_mapel_id' => $data['kelas_mapel_id'],
                'user_id' => auth()->id(),
            ]);

            // Handle file uploads
            if ($files) {
                $this->handleFileUploads($tugas->id, $files);
            }

            return $tugas;
        });
    }

    /**
     * Update existing tugas
     */
    public function updateTugas(Tugas $tugas, array $data, $files = null)
    {
        return DB::transaction(function () use ($tugas, $data, $files) {
            $tugas->update([
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'deadline' => $data['deadline'],
                'tipe_tugas' => $data['tipe_tugas'],
            ]);

            // Handle new file uploads
            if ($files) {
                $this->handleFileUploads($tugas->id, $files);
            }

            return $tugas;
        });
    }

    /**
     * Get tugas with eager loading
     */
    public function getTugasWithRelations($tugasId)
    {
        return Tugas::with([
            'kelasMapel.kelas',
            'kelasMapel.mapel',
            'tugasFiles',
            'user'
        ])->findOrFail($tugasId);
    }

    /**
     * Get tugas for specific user role
     */
    public function getTugasForUser($userId, $roleId)
    {
        $query = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel', 'tugasFiles']);

        if ($roleId == 2) { // Pengajar
            $query->where('user_id', $userId);
        } elseif ($roleId == 3) { // Siswa
            $user = auth()->user();
            $kelasId = $user->kelas_id;
            
            $query->whereHas('kelasMapel', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Handle file uploads for tugas
     */
    private function handleFileUploads($tugasId, $files)
    {
        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tugas', $filename, 'public');

            TugasFile::create([
                'tugas_id' => $tugasId,
                'nama_file' => $filename,
                'path' => $path,
                'ukuran_file' => $file->getSize(),
            ]);
        }
    }

    /**
     * Delete tugas and related files
     */
    public function deleteTugas(Tugas $tugas)
    {
        return DB::transaction(function () use ($tugas) {
            // Delete files from storage
            foreach ($tugas->tugasFiles as $file) {
                Storage::disk('public')->delete($file->path);
            }

            // Delete tugas (cascade will handle related records)
            $tugas->delete();
        });
    }

    /**
     * Check if user has access to tugas
     */
    public function hasAccessToTugas($tugasId, $userId, $roleId)
    {
        $tugas = Tugas::findOrFail($tugasId);

        if ($roleId == 1) { // Admin
            return true;
        } elseif ($roleId == 2) { // Pengajar
            return $tugas->user_id == $userId;
        } elseif ($roleId == 3) { // Siswa
            $user = auth()->user();
            return $tugas->kelasMapel->kelas_id == $user->kelas_id;
        }

        return false;
    }
}
