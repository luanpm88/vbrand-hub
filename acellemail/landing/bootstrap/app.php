<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // i18n: detect locale from URL prefix on every web request.
        // 404s when the URL prefix matches a locale that is not enabled
        // and when the URL hits an unpublished locale route (per-route gate).
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // The `auth` middleware redirects guests to a 'login' route by
        // default; our auth surface (Wave 0) uses `auth.login`. Set the
        // redirect target explicitly so /admin and other auth-gated
        // pages 302 instead of crashing with RouteNotFoundException.
        $middleware->redirectGuestsTo(fn () => route('auth.login'));

        // `admin` alias — gate routes that require `users.is_admin = 1`.
        // Used by the /admin/* group in routes/web.php.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        // Wave 3 — `cta_dismissed` is written by client-side JS in
        // public/js/script.js (it has no access to Laravel's app key
        // and shouldn't), so Laravel's EncryptCookies middleware must
        // skip it on read — otherwise the decrypt fails silently and
        // request()->cookie('cta_dismissed') returns null, defeating
        // the 7-day dismissal-stickiness contract documented in
        // CTA_VARIANT_COPY.md.
        $middleware->encryptCookies(except: ['cta_dismissed']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
