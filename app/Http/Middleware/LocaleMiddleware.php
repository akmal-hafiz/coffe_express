<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Supported locales.
     */
    protected array $supportedLocales = ['en', 'id'];

    /**
     * Default locale.
     */
    protected string $defaultLocale = 'id';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority order for locale detection:
        // 1. Query parameter (?lang=id)
        // 2. Session
        // 3. Cookie
        // 4. Browser preference
        // 5. Default locale

        $locale = $this->getLocale($request);

        // Validate the locale
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = $this->defaultLocale;
        }

        // Set the application locale
        App::setLocale($locale);

        // Store in session for persistence
        Session::put('locale', $locale);

        // Share locale with all views
        view()->share('currentLocale', $locale);
        view()->share('supportedLocales', $this->getSupportedLocalesWithNames());

        $response = $next($request);

        // Set cookie for 1 year
        if ($response instanceof Response) {
            $response->headers->setCookie(
                cookie('locale', $locale, 60 * 24 * 365)
            );
        }

        return $response;
    }

    /**
     * Get the locale from various sources.
     */
    protected function getLocale(Request $request): string
    {
        // 1. Check query parameter
        if ($request->has('lang')) {
            $queryLocale = $request->query('lang');
            if (in_array($queryLocale, $this->supportedLocales)) {
                return $queryLocale;
            }
        }

        // 2. Check session
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            if (in_array($sessionLocale, $this->supportedLocales)) {
                return $sessionLocale;
            }
        }

        // 3. Check cookie
        if ($request->hasCookie('locale')) {
            $cookieLocale = $request->cookie('locale');
            if (in_array($cookieLocale, $this->supportedLocales)) {
                return $cookieLocale;
            }
        }

        // 4. Check browser preference
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale && in_array($browserLocale, $this->supportedLocales)) {
            return $browserLocale;
        }

        // 5. Return default locale
        return $this->defaultLocale;
    }

    /**
     * Get locale from browser Accept-Language header.
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $language) {
            // Get language code (before any ;q= quality value)
            $lang = trim(explode(';', $language)[0]);

            // Get just the primary language code (e.g., 'en' from 'en-US')
            $primaryLang = strtolower(explode('-', $lang)[0]);

            if (in_array($primaryLang, $this->supportedLocales)) {
                return $primaryLang;
            }
        }

        return null;
    }

    /**
     * Get supported locales with their display names.
     */
    protected function getSupportedLocalesWithNames(): array
    {
        return [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇬🇧',
            ],
            'id' => [
                'code' => 'id',
                'name' => 'Indonesian',
                'native' => 'Bahasa Indonesia',
                'flag' => '🇮🇩',
            ],
        ];
    }
}
