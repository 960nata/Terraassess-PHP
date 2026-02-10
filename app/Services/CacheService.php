<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;

class CacheService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 15; // 15 minutes

    /**
     * Get cached task statistics for a user
     */
    public static function getTaskStats($userId)
    {
        return Cache::remember("task_stats_user_{$userId}", self::CACHE_DURATION, function() use ($userId) {
            $baseQuery = Tugas::whereHas('KelasMapel', function($query) use ($userId) {
                $query->whereHas('EditorAccess', function($editorQuery) use ($userId) {
                    $editorQuery->where('user_id', $userId);
                });
            });
            
            return [
                'total' => $baseQuery->count(),
                'active' => (clone $baseQuery)->where('isHidden', 0)->count(),
                'completed' => (clone $baseQuery)->where('due', '<', now())->count(),
                'draft' => (clone $baseQuery)->where('isHidden', 1)->count(),
            ];
        });
    }

    /**
     * Get cached task type statistics
     */
    public static function getTaskTypeStats()
    {
        return Cache::remember('task_type_stats', self::CACHE_DURATION, function() {
            $stats = Tugas::selectRaw('tipe, COUNT(*) as count')
                ->groupBy('tipe')
                ->pluck('count', 'tipe')
                ->toArray();
            
            return [
                'multiple_choice' => $stats[1] ?? 0,
                'essay' => $stats[2] ?? 0,
                'individual' => $stats[3] ?? 0,
                'group' => $stats[4] ?? 0,
            ];
        });
    }

    /**
     * Get cached classes for a user
     */
    public static function getUserClasses($userId)
    {
        return Cache::remember("user_classes_{$userId}", self::CACHE_DURATION, function() use ($userId) {
            return Kelas::whereHas('users', function($query) use ($userId) {
                $query->where('id', $userId);
            })->get();
        });
    }

    /**
     * Get cached subjects for a user
     */
    public static function getUserSubjects($userId)
    {
        return Cache::remember("user_subjects_{$userId}", self::CACHE_DURATION, function() use ($userId) {
            return Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($userId) {
                $query->where('id', $userId);
            })->get();
        });
    }

    /**
     * Clear all task-related caches for a user
     */
    public static function clearUserTaskCaches($userId)
    {
        Cache::forget("task_stats_user_{$userId}");
        Cache::forget("user_classes_{$userId}");
        Cache::forget("user_subjects_{$userId}");
    }

    /**
     * Clear all task type statistics cache
     */
    public static function clearTaskTypeStatsCache()
    {
        Cache::forget('task_type_stats');
    }

    /**
     * Clear all caches
     */
    public static function clearAllCaches()
    {
        Cache::flush();
    }
}
