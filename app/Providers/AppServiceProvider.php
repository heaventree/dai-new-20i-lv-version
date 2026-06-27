<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $request = request();
        if ($request->hasHeader('X-Forwarded-Host')) {
            $scheme = $request->header('X-Forwarded-Proto', 'https');
            $host   = $request->header('X-Forwarded-Host');
            \Illuminate\Support\Facades\URL::forceRootUrl("{$scheme}://{$host}");
            \Illuminate\Support\Facades\URL::forceScheme($scheme);
        } else {
            $appUrl = config('app.url');
            if ($appUrl && !str_starts_with($appUrl, 'http://localhost')) {
                \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
                if (str_starts_with($appUrl, 'https://')) {
                    \Illuminate\Support\Facades\URL::forceScheme('https');
                }
            }
        }
    }
}
