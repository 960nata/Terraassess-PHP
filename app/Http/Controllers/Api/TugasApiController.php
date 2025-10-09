<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Services\TugasService;
use App\Repositories\TugasRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TugasApiController extends Controller
{
    protected $tugasService;
    protected $tugasRepository;

    public function __construct(TugasService $tugasService, TugasRepository $tugasRepository)
    {
        $this->tugasService = $tugasService;
        $this->tugasRepository = $tugasRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            $roleId = auth()->user()->roles_id;
            $perPage = $request->get('per_page', 10);

            if ($roleId == 2) { // Pengajar
                $tugas = $this->tugasRepository->getForPengajar($userId, $perPage);
            } elseif ($roleId == 3) { // Siswa
                $kelasId = auth()->user()->kelas_id;
                $tugas = $this->tugasRepository->getForSiswa($kelasId, $perPage);
            } else { // Admin
                $tugas = $this->tugasRepository->getAllWithRelations();
            }

            return response()->json([
                'success' => true,
                'data' => $tugas,
                'message' => 'Tugas berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTugasRequest $request): JsonResponse
    {
        try {
            $tugas = $this->tugasService->createTugas($request->validated(), $request->file('file_tugas'));

            return response()->json([
                'success' => true,
                'data' => $tugas->load(['kelasMapel.kelas', 'kelasMapel.mapel', 'tugasFiles']),
                'message' => 'Tugas berhasil dibuat'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tugas = $this->tugasRepository->findByIdWithRelations($id);

            if (!$tugas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Check access
            if (!$this->tugasService->hasAccessToTugas($id, auth()->id(), auth()->user()->roles_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke tugas ini'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $tugas,
                'message' => 'Tugas berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTugasRequest $request, int $id): JsonResponse
    {
        try {
            $tugas = $this->tugasRepository->findByIdWithRelations($id);

            if (!$tugas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Check access
            if (!$this->tugasService->hasAccessToTugas($id, auth()->id(), auth()->user()->roles_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke tugas ini'
                ], 403);
            }

            $tugas = $this->tugasService->updateTugas($tugas, $request->validated(), $request->file('file_tugas'));

            return response()->json([
                'success' => true,
                'data' => $tugas->load(['kelasMapel.kelas', 'kelasMapel.mapel', 'tugasFiles']),
                'message' => 'Tugas berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $tugas = $this->tugasRepository->findByIdWithRelations($id);

            if (!$tugas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas tidak ditemukan'
                ], 404);
            }

            // Check access
            if (!$this->tugasService->hasAccessToTugas($id, auth()->id(), auth()->user()->roles_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke tugas ini'
                ], 403);
            }

            $this->tugasService->deleteTugas($tugas);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tugas statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->tugasRepository->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistik tugas berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search tugas
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $perPage = $request->get('per_page', 10);

            $tugas = $this->tugasRepository->search($query, $perPage);

            return response()->json([
                'success' => true,
                'data' => $tugas,
                'message' => 'Hasil pencarian tugas'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari tugas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming deadlines
     */
    public function upcomingDeadlines(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 7);
            $tugas = $this->tugasRepository->getUpcomingDeadlines($days);

            return response()->json([
                'success' => true,
                'data' => $tugas,
                'message' => 'Tugas dengan deadline mendatang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil tugas dengan deadline mendatang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
