<?php

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))

    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AdminPanelProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            App\Http\Middleware\RemovePublicFromUrl::class,
            App\Http\Middleware\AddSeoDefaults::class,
            App\Http\Middleware\ForceHttps::class,
            App\Http\Middleware\RedirectIfFromGoogle::class,
            App\Http\Middleware\GoogleTranslate::class,
        ]);

        $middleware->redirectTo(fn () => Filament\Pages\Dashboard::getUrl());
    })
    ->withExceptions(function (Exceptions $exceptions) {
    //       $exceptions->renderable(function (NotFoundHttpException $e, $request) {
    //     return redirect('/', 302); // Use 301 for permanent redirect
    // });
        $exceptions->reportable(fn (Throwable $e) => $exceptions->handler->shouldReport($e) &&
            FilamentExceptions::report($e)
        );
    })->create();
