<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (config('database.default') === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && $dbPath !== ':memory:') {
                    $dir = dirname($dbPath);
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0777, true);
                    }
                    @chmod($dir, 0777);

                    // Check if directory or file is read-only (common in Docker/Nixpacks where /app/database is root:root 755)
                    $isDirWritable = is_dir($dir) && is_writable($dir);
                    $isFileWritable = file_exists($dbPath) ? is_writable($dbPath) : $isDirWritable;

                    if (!$isDirWritable || !$isFileWritable) {
                        $safeDbPath = storage_path('database.sqlite');
                        $storageDir = storage_path();
                        if (!is_dir($storageDir)) {
                            @mkdir($storageDir, 0777, true);
                        }
                        @chmod($storageDir, 0777);

                        // If original file exists, copy schema and data to writable storage
                        if (file_exists($dbPath) && !file_exists($safeDbPath)) {
                            @copy($dbPath, $safeDbPath);
                        }

                        if (!file_exists($safeDbPath)) {
                            @touch($safeDbPath);
                        }
                        @chmod($safeDbPath, 0666);

                        // Point connection to writable storage database
                        config(['database.connections.sqlite.database' => $safeDbPath]);
                        \Illuminate\Support\Facades\DB::purge('sqlite');
                        $dbPath = $safeDbPath;
                    }

                    if (!file_exists($dbPath)) {
                        @touch($dbPath);
                        @chmod($dbPath, 0666);
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    }
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('quests')) {
                \App\Services\QuestDataService::syncDatabase();
            }
        } catch (\Throwable $e) {
            // Silently continue during early CLI setup or migrations
        }
    }
}
