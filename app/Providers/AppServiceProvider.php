<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $host = request()->getHost();
        if (str($host)->endsWith('.kalourmade.com') || $host === 'kalourmade.com') {
            URL::forceScheme('https');
        }
    }

    public function boot(): void
    {
        // if (request()->getHost() === 'kalourmade.com') {
        //     URL::forceScheme('https');
        // }
    }
}
