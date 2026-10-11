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
            if (\Illuminate\Support\Facades\Schema::hasTable('quests')) {
                \App\Services\QuestDataService::syncDatabase();
            }
        } catch (\Throwable $e) {
            // Silently continue during early CLI setup or migrations
        }
    }
}
