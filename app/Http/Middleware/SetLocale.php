<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    private const SUPPORTED = ['en', 'hi', 'kn', 'ta'];

    public function handle(Request $request, Closure $next)
    {
        // Cookie is primary (persists across login/logout), session is secondary
        $locale = $request->cookie('app_locale')
               ?? session('locale')
               ?? config('app.locale', 'en');

        if (!in_array($locale, self::SUPPORTED)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
