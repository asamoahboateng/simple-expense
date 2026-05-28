<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Events\App\ApplicationBooted;
use Native\Laravel\Facades\Window;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Called by NativePHP when the Electron app boots.
     * NativePHP already handles SQLite DB creation + default connection via
     * its internal NativeServiceProvider::rewriteDatabase() — do NOT override
     * database.default or database.connections here.
     */
    public function boot(): void
    {
        // File-based session & cache are safe for a single-user desktop app.
        // NativePHP has already set queue.default => database by this point.
        config([
            'session.driver' => 'file',
            'cache.default'  => 'file',
        ]);

        // Listen for the "app fully booted" event to run any pending
        // migrations against the user's SQLite database on every launch.
        \Illuminate\Support\Facades\Event::listen(
            ApplicationBooted::class,
            function () {
                // NativePHP stores the DB in the user's app-data folder in
                // production, or at database/nativephp.sqlite in dev mode.
                // Either way, native:migrate targets the correct connection.
                Artisan::call('native:migrate', ['--force' => true]);
            }
        );

        Window::open()
            ->title('Simple Expense')
            ->width(1280)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600);
    }

    /**
     * PHP ini settings to apply inside the Electron-bundled PHP binary.
     */
    public function phpIni(): array
    {
        return [];
    }
}
