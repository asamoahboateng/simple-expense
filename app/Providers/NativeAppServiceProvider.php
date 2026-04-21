<?php

namespace App\Providers;

use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\Window;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    public function boot(): void
    {
        // Use SQLite, file-based session/cache when running as desktop app
        config([
            'database.default' => 'sqlite',
            'session.driver' => 'file',
            'cache.default' => 'file',
            'queue.default' => 'sync',
        ]);

        Window::open()
            ->title('Simple Expense')
            ->width(1280)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600);
    }

    public function phpIni(): array
    {
        return [];
    }
}
