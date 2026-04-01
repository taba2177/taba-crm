<?php

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Taba\Crm\Exceptions\Handler;

return Application::configure(basePath: dirname(__DIR__))

    ->withProviders([
        Taba\Crm\Providers\filament\AdminPanelProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            Taba\Crm\Http\Middleware\RemovePublicFromUrl::class,
            Taba\Crm\Http\Middleware\AddSeoDefaults::class,
            Taba\Crm\Http\Middleware\ForceHttps::class,
            Taba\Crm\Http\Middleware\RedirectIfFromGoogle::class,
            Taba\Crm\Http\Middleware\GoogleTranslate::class,
        ]);

        $middleware->redirectTo(fn () => Filament\Pages\Dashboard::getUrl());
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->handler(function ($e) {
            return app(Handler::class)->render(request(), $e);
        });

        // You can also report exceptions using your handler if needed
        $exceptions->report(function (Throwable $e) {
            app(Handler::class)->report($e);
        });
        $exceptions->reportable(fn (Throwable $e) => $exceptions->handler->shouldReport($e) &&
            FilamentExceptions::report($e)
        );
    })->create();
