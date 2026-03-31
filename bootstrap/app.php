<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Taba\Crm\Exceptions\ApiExceptionHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Ensure Sanctum stateful middleware is applied for API routes
        $middleware->statefulApi();

        // Add CORS middleware globally
        $middleware->api(prepend: [
            \Taba\Crm\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Clean JSON errors for all API routes (critical for Angular)
        $exceptions->renderable(function (ModelNotFoundException $e, $request) {
            return ApiExceptionHandler::modelNotFound($e, $request);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            return ApiExceptionHandler::notFound($e, $request);
        });

        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return ApiExceptionHandler::unauthenticated($e, $request);
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return ApiExceptionHandler::methodNotAllowed($e, $request);
        });

        $exceptions->renderable(function (TooManyRequestsHttpException $e, $request) {
            return ApiExceptionHandler::tooManyRequests($e, $request);
        });
    })->create();
