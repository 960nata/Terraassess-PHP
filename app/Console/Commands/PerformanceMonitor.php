<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;

class PerformanceMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:monitor {--clear-cache : Clear all caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor application performance and cache status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Performance Monitor');
        $this->line('==================');

        if ($this->option('clear-cache')) {
            $this->clearCaches();
            return;
        }

        $this->checkDatabasePerformance();
        $this->checkCacheStatus();
        $this->checkQueryPerformance();
    }

    /**
     * Check database performance
     */
    private function checkDatabasePerformance()
    {
        $this->info('📊 Database Performance');
        $this->line('----------------------');

        try {
            // Check table sizes
            $tables = ['tugas', 'tugas_progress', 'users', 'kelas', 'mapels', 'kelas_mapels'];
            
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->line("📋 {$table}: {$count} records");
                }
            }

            // Check indexes
            $this->line("\n🔍 Checking indexes...");
            $indexes = DB::select("SHOW INDEX FROM tugas");
            $this->line("✅ Tugas table has " . count($indexes) . " indexes");

        } catch (\Exception $e) {
            $this->error("❌ Database error: " . $e->getMessage());
        }
    }

    /**
     * Check cache status
     */
    private function checkCacheStatus()
    {
        $this->info("\n💾 Cache Status");
        $this->line('---------------');

        try {
            // Test cache functionality
            $testKey = 'performance_test_' . time();
            Cache::put($testKey, 'test_value', 60);
            $retrieved = Cache::get($testKey);
            
            if ($retrieved === 'test_value') {
                $this->line('✅ Cache is working properly');
            } else {
                $this->error('❌ Cache is not working properly');
            }

            Cache::forget($testKey);

            // Check cache driver
            $driver = config('cache.default');
            $this->line("📦 Cache driver: {$driver}");

        } catch (\Exception $e) {
            $this->error("❌ Cache error: " . $e->getMessage());
        }
    }

    /**
     * Check query performance
     */
    private function checkQueryPerformance()
    {
        $this->info("\n⚡ Query Performance");
        $this->line('-------------------');

        try {
            // Test a common query
            $start = microtime(true);
            
            $tasks = DB::table('tugas')
                ->join('kelas_mapels', 'tugas.kelas_mapel_id', '=', 'kelas_mapels.id')
                ->join('kelas', 'kelas_mapels.kelas_id', '=', 'kelas.id')
                ->select('tugas.*', 'kelas.name as kelas_name')
                ->limit(10)
                ->get();

            $end = microtime(true);
            $executionTime = round(($end - $start) * 1000, 2);

            $this->line("📊 Query execution time: {$executionTime}ms");
            $this->line("📋 Retrieved {$tasks->count()} tasks");

            if ($executionTime < 100) {
                $this->line('✅ Query performance is good');
            } elseif ($executionTime < 500) {
                $this->line('⚠️  Query performance is acceptable');
            } else {
                $this->line('❌ Query performance needs improvement');
            }

        } catch (\Exception $e) {
            $this->error("❌ Query error: " . $e->getMessage());
        }
    }

    /**
     * Clear all caches
     */
    private function clearCaches()
    {
        $this->info('🧹 Clearing Caches');
        $this->line('------------------');

        try {
            CacheService::clearAllCaches();
            $this->line('✅ All caches cleared successfully');
        } catch (\Exception $e) {
            $this->error("❌ Error clearing caches: " . $e->getMessage());
        }
    }
}