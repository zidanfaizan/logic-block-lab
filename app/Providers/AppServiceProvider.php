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
