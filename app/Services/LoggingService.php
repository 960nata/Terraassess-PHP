<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoggingService
{
    /**
     * Log user activity
     */
    public static function logUserActivity(string $action, array $data = [], ?Request $request = null): void
    {
        $user = Auth::user();
        
        Log::info('User Activity', [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'action' => $action,
            'data' => $data,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log API request
     */
    public static function logApiRequest(Request $request, $response = null): void
    {
        $user = Auth::user();
        
        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => $user?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_data' => $request->except(['password', 'password_confirmation']),
            'response_status' => $response?->getStatusCode(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log database query
     */
    public static function logDatabaseQuery(string $query, array $bindings = [], float $time = 0): void
    {
        if (config('app.debug')) {
            Log::debug('Database Query', [
                'query' => $query,
                'bindings' => $bindings,
                'execution_time' => $time . 'ms',
                'timestamp' => now()->toISOString(),
            ]);
        }
    }

    /**
     * Log file operation
     */
    public static function logFileOperation(string $operation, string $filePath, array $data = []): void
    {
        $user = Auth::user();
        
        Log::info('File Operation', [
            'operation' => $operation,
            'file_path' => $filePath,
            'user_id' => $user?->id,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent(string $event, array $data = [], ?Request $request = null): void
    {
        Log::warning('Security Event', [
            'event' => $event,
            'data' => $data,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log performance metrics
     */
    public static function logPerformance(string $operation, float $executionTime, array $metrics = []): void
    {
        Log::info('Performance Metrics', [
            'operation' => $operation,
            'execution_time' => $executionTime . 'ms',
            'metrics' => $metrics,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log error with context
     */
    public static function logError(\Throwable $exception, array $context = []): void
    {
        $user = Auth::user();
        
        Log::error('Application Error', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => $user?->id,
            'context' => $context,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log business logic event
     */
    public static function logBusinessEvent(string $event, array $data = []): void
    {
        $user = Auth::user();
        
        Log::info('Business Event', [
            'event' => $event,
            'data' => $data,
            'user_id' => $user?->id,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get log statistics
     */
    public static function getLogStatistics(int $days = 7): array
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            return [];
        }

        $logs = file_get_contents($logPath);
        $lines = explode("\n", $logs);
        
        $stats = [
            'total_entries' => count($lines),
            'error_count' => 0,
            'warning_count' => 0,
            'info_count' => 0,
            'debug_count' => 0,
        ];

        foreach ($lines as $line) {
            if (strpos($line, 'ERROR') !== false) {
                $stats['error_count']++;
            } elseif (strpos($line, 'WARNING') !== false) {
                $stats['warning_count']++;
            } elseif (strpos($line, 'INFO') !== false) {
                $stats['info_count']++;
            } elseif (strpos($line, 'DEBUG') !== false) {
                $stats['debug_count']++;
            }
        }

        return $stats;
    }
}
