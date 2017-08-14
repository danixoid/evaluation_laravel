<?php

namespace App\Http\Middleware;

use Closure;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $raw_locale = session()->get('locale');
        if (array_key_exists($raw_locale, config()->get('app.locales'))) {
            $locale = $raw_locale;
        }
        else $locale = config()->get('app.locale');
        app()->setLocale($locale);

        return $next($request);
    }
}
