<?php

namespace Modules\Category\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Category';

    public function boot(): void
    {
        $this->loadMigrations();
        $this->mergeConfigFrom(
            module_path('Category', 'config/settings.php'),
            'settings'
        );
        Route::middleware('web')->group(module_path('Category', 'routes/web.php'));
    }

    public function register(): void {}

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Migrations'));
    }
}
