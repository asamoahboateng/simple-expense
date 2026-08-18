<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $host = request()->getHost();
        $olaHost = env('HOST_DOMAIN', 'kalourmade.com');
        if (str($host)->endsWith('.' . $olaHost) || $host === $olaHost) {
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
