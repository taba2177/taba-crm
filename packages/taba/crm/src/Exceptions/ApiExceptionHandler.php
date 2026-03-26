<?php

namespace Taba\Crm\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * API Exception handler — register these renderers in CrmServiceProvider
 * to ensure all API routes return clean JSON errors for Angular.
 */
class ApiExceptionHandler
{
    /**
     * Register exception renderers for API routes.
     */
    public static function register(\Illuminate\Foundation\Configuration\Exceptions $exceptions): void
    {
        // These are registered via the service provider's boot() method
    }

    /**
     * Render a ModelNotFoundException as a 404 JSON.
     */
    public static function modelNotFound(ModelNotFoundException $e, Request $request): ?JsonResponse
    {
        if (!$request->is('api/*')) {
            return null;
        }

        $modelName = class_basename($e->getModel());

        return response()->json([
            'success' => false,
            'message' => "{$modelName} not found",
        ], 404);
    }

    /**
     * Render a NotFoundHttpException as a 404 JSON.
     */
    public static function notFound(NotFoundHttpException $e, Request $request): ?JsonResponse
    {
        if (!$request->is('api/*')) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Endpoint not found',
        ], 404);
    }

    /**
     * Render an AuthenticationException as a 401 JSON.
     */
    public static function unauthenticated(AuthenticationException $e, Request $request): ?JsonResponse
    {
        if (!$request->is('api/*')) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated. Please provide a valid Bearer token.',
        ], 401);
    }

    /**
     * Render a MethodNotAllowedHttpException.
     */
    public static function methodNotAllowed(MethodNotAllowedHttpException $e, Request $request): ?JsonResponse
    {
        if (!$request->is('api/*')) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Method not allowed',
        ], 405);
    }

    /**
     * Render a TooManyRequestsHttpException (429).
     */
    public static function tooManyRequests(TooManyRequestsHttpException $e, Request $request): ?JsonResponse
    {
        if (!$request->is('api/*')) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Too many requests. Please try again later.',
            'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
        ], 429);
    }
}
