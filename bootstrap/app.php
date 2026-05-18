<?php

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->header('X-Livewire')) {
                return response()->json([
                    'message' => 'Something went wrong. Please refresh the page.',
                ], 500);
            }

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                if (in_array($status, [404, 419, 500, 503], true)
                    && view()->exists("errors.{$status}")) {
                    return response()->view("errors.{$status}", [], $status);
                }
            }
        });

        $exceptions->reportable(function (Throwable $e) {
            return FilamentExceptions::report($e);
        });
    })->create();
