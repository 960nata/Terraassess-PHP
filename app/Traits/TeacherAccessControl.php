<?php

namespace App\Traits;

use App\Models\EditorAccess;
use Illuminate\Http\Request;

trait TeacherAccessControl
{
    /**
     * Get teacher's assigned classes and subjects
     */
    protected function getTeacherAssignedData(Request $request)
    {
        $user = auth()->user();
        
        if ($user->roles_id !== 3) {
            return null;
        }

        // Get from request if already set by middleware
        if ($request->has('teacher_assigned_kelas_mapel')) {
            return [
                'kelas_mapel_ids' => $request->get('teacher_assigned_kelas_mapel', []),
                'kelas_ids' => $request->get('teacher_assigned_kelas', []),
                'mapel_ids' => $request->get('teacher_assigned_mapel', [])
            ];
        }

        // Fallback: query directly
        $assignedKelasMapel = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get();

        return [
            'kelas_mapel_ids' => $assignedKelasMapel->pluck('kelas_mapel_id')->toArray(),
            'kelas_ids' => $assignedKelasMapel->pluck('kelasMapel.kelas_id')->unique()->toArray(),
            'mapel_ids' => $assignedKelasMapel->pluck('kelasMapel.mapel_id')->unique()->toArray()
        ];
    }

    /**
     * Filter materials by teacher's assigned classes
     */
    protected function filterMaterialsByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
    }

    /**
     * Filter tasks by teacher's assigned classes
     */
    protected function filterTasksByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
    }

    /**
     * Filter exams by teacher's assigned classes
     */
    protected function filterExamsByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
    }

    /**
     * Filter classes by teacher's assigned classes
     */
    protected function filterClassesByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('id', $assignedData['kelas_ids']);
    }

    /**
     * Filter subjects by teacher's assigned subjects
     */
    protected function filterSubjectsByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['mapel_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('id', $assignedData['mapel_ids']);
    }

    /**
     * Filter class-subject combinations by teacher's assignments
     */
    protected function filterKelasMapelByTeacherAccess($query, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('id', $assignedData['kelas_mapel_ids']);
    }

    /**
     * Get available classes for teacher
     */
    protected function getTeacherAvailableClasses(Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_ids'])) {
            return collect();
        }

        return \App\Models\Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
    }

    /**
     * Get available subjects for teacher
     */
    protected function getTeacherAvailableSubjects(Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['mapel_ids'])) {
            return collect();
        }

        return \App\Models\Mapel::whereIn('id', $assignedData['mapel_ids'])->get();
    }

    /**
     * Get available class-subject combinations for teacher
     */
    protected function getTeacherAvailableKelasMapel(Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return collect();
        }

        return \App\Models\KelasMapel::whereIn('id', $assignedData['kelas_mapel_ids'])
            ->with(['kelas', 'mapel'])
            ->get();
    }

    /**
     * Check if teacher has access to specific class-subject combination
     */
    protected function teacherHasAccessToKelasMapel($kelasMapelId, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return false;
        }

        return in_array($kelasMapelId, $assignedData['kelas_mapel_ids']);
    }

    /**
     * Check if teacher has access to specific class
     */
    protected function teacherHasAccessToClass($kelasId, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_ids'])) {
            return false;
        }

        return in_array($kelasId, $assignedData['kelas_ids']);
    }

    /**
     * Check if teacher has access to specific subject
     */
    protected function teacherHasAccessToSubject($mapelId, Request $request)
    {
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['mapel_ids'])) {
            return false;
        }

        return in_array($mapelId, $assignedData['mapel_ids']);
    }
}
