<?php

/*
|--------------------------------------------------------------------------
| Taba CRM API Routes (v1)
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api/v1 and use the 'api' middleware group.
| Public routes are accessible without authentication.
| Protected routes require a valid Sanctum Bearer token.
|
| Angular Usage:
|   - Set base URL: environment.apiUrl = 'https://your-domain.com/api/v1'
|   - Set headers: Authorization: Bearer <token>
|   - Set headers: Accept: application/json
|   - Set headers: Accept-Language: ar|en
|
*/

use Illuminate\Support\Facades\Route;
use Taba\Crm\Http\Controllers\Api\ActionClickApiController;
use Taba\Crm\Http\Controllers\Api\AuthApiController;
use Taba\Crm\Http\Controllers\Api\CategoryApiController;
use Taba\Crm\Http\Controllers\Api\ContactEntryApiController;
use Taba\Crm\Http\Controllers\Api\HomeApiController;
use Taba\Crm\Http\Controllers\Api\PreviewApiController;
use Taba\Crm\Http\Controllers\Api\MenuApiController;
use Taba\Crm\Http\Controllers\Api\PageApiController;
use Taba\Crm\Http\Controllers\Api\PostApiController;
use Taba\Crm\Http\Controllers\Api\ReviewApiController;
use Taba\Crm\Http\Controllers\Api\ServicePaymentApiController;
use Taba\Crm\Http\Controllers\Api\SettingApiController;
use Taba\Crm\Http\Controllers\Api\TagApiController;

Route::prefix('api/v1')->middleware(['api', \Taba\Crm\Http\Middleware\SetLocaleFromHeader::class])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Public routes (no auth required)
    |----------------------------------------------------------------------
    */

    // Bootstrap / Init — throttled at 100 req/min
    Route::middleware('throttle:100,1')->group(function () {
        Route::get('init', [HomeApiController::class, 'init']);
        Route::get('home', [HomeApiController::class, 'home']);
        Route::get('preview/{key}', [PreviewApiController::class, 'home']);

        // Posts (public read)
        Route::get('posts',                [PostApiController::class, 'index']);
        Route::get('posts/{slug}',         [PostApiController::class, 'show']);
        Route::get('posts/{slug}/related', [PostApiController::class, 'related']);

        // Categories (public read)
        Route::get('categories',                       [CategoryApiController::class, 'index']);
        Route::get('categories/{slug}',                [CategoryApiController::class, 'show']);
        Route::get('categories/{slug}/posts',          [CategoryApiController::class, 'posts']);
        Route::get('categories/{category}/{post}',     [PostApiController::class, 'showByCategory']);

        // Pages (public read)
        Route::get('pages',        [PageApiController::class, 'index']);
        Route::get('pages/{slug}', [PageApiController::class, 'show']);

        // Tags (public read)
        Route::get('tags',              [TagApiController::class, 'index']);
        Route::get('tags/{slug}',       [TagApiController::class, 'show']);
        Route::get('tags/{slug}/posts', [TagApiController::class, 'posts']);

        // Menus (public read)
        Route::get('menus',        [MenuApiController::class, 'index']);
        Route::get('menus/{menu}', [MenuApiController::class, 'show']);

        // Reviews (public read)
        Route::get('reviews',          [ReviewApiController::class, 'index']);
        Route::get('reviews/{review}', [ReviewApiController::class, 'show']);

        // Settings (public read)
        Route::get('settings',         [SettingApiController::class, 'index']);
        Route::get('settings/grouped', [SettingApiController::class, 'grouped']);
        Route::get('settings/{key}',   [SettingApiController::class, 'show']);
    });

    // Authentication (separate group — no read throttle)
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthApiController::class, 'register']);
        Route::post('login',    [AuthApiController::class, 'login']);
    });

    // Contact (write — stricter limit: 30 req/min)
    Route::post('contact', [ContactEntryApiController::class, 'store'])
        ->middleware('throttle:30,1');

    // Action click tracking (public, rate-limited)
    Route::middleware('throttle:60,1')->post('actions', [ActionClickApiController::class, 'store']);

    /*
    |----------------------------------------------------------------------
    | Protected routes (Sanctum auth required)
    |----------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Auth actions
        Route::prefix('auth')->group(function () {
            Route::post('logout',          [AuthApiController::class, 'logout']);
            Route::get('me',               [AuthApiController::class, 'me']);
            Route::put('me',               [AuthApiController::class, 'updateProfile']);
            Route::post('change-password', [AuthApiController::class, 'changePassword']);
            Route::post('refresh',         [AuthApiController::class, 'refresh']);
        });

        // Posts (CRUD)
        Route::post('posts',           [PostApiController::class, 'store']);
        Route::put('posts/{post}',     [PostApiController::class, 'update']);
        Route::delete('posts/{post}',  [PostApiController::class, 'destroy']);

        // Categories (CRUD)
        Route::post('categories',              [CategoryApiController::class, 'store']);
        Route::put('categories/{category}',    [CategoryApiController::class, 'update']);
        Route::delete('categories/{category}', [CategoryApiController::class, 'destroy']);

        // Pages (CRUD)
        Route::post('pages',          [PageApiController::class, 'store']);
        Route::put('pages/{page}',    [PageApiController::class, 'update']);
        Route::delete('pages/{page}', [PageApiController::class, 'destroy']);

        // Tags (CRUD)
        Route::post('tags',         [TagApiController::class, 'store']);
        Route::put('tags/{tag}',    [TagApiController::class, 'update']);
        Route::delete('tags/{tag}', [TagApiController::class, 'destroy']);

        // Menus (CRUD)
        Route::post('menus',          [MenuApiController::class, 'store']);
        Route::put('menus/{menu}',    [MenuApiController::class, 'update']);
        Route::delete('menus/{menu}', [MenuApiController::class, 'destroy']);

        // Reviews (CRUD)
        Route::post('reviews',            [ReviewApiController::class, 'store']);
        Route::put('reviews/{review}',    [ReviewApiController::class, 'update']);
        Route::delete('reviews/{review}', [ReviewApiController::class, 'destroy']);

        // Settings (admin write)
        Route::put('settings/{key}', [SettingApiController::class, 'update']);

        // Contact entries (admin read/delete)
        Route::get('contact-entries',                  [ContactEntryApiController::class, 'index']);
        Route::get('contact-entries/{contactEntry}',   [ContactEntryApiController::class, 'show']);
        Route::delete('contact-entries/{contactEntry}', [ContactEntryApiController::class, 'destroy']);

        // Service Payments
        Route::get('payments',              [ServicePaymentApiController::class, 'index']);
        Route::get('payments/{servicePayment}', [ServicePaymentApiController::class, 'show']);
        Route::post('payments',             [ServicePaymentApiController::class, 'store']);

        // Action click summary (admin)
        Route::get('actions/summary', [ActionClickApiController::class, 'summary']);
    });
});

/*
|--------------------------------------------------------------------------
| Taba CRM API Routes (v2) — Component-Aware
|--------------------------------------------------------------------------
*/

use Taba\Crm\Http\Controllers\Api\V2 as V2;

Route::prefix('api/v2')->middleware(['api', \Taba\Crm\Http\Middleware\SetLocaleFromHeader::class])->group(function () {

    // Public read endpoints
    Route::middleware('throttle:100,1')->group(function () {
        Route::get('sections', [V2\SectionController::class, 'index']);
        Route::get('sections/{id}', [V2\SectionController::class, 'show']);
        Route::get('settings', [V2\SettingController::class, 'index']);
        Route::get('pages/{slug}', [V2\PageController::class, 'show']);
        Route::get('menus', [V2\MenuController::class, 'index']);
        Route::get('components', [V2\ComponentController::class, 'index']);
    });

    // Contact (stricter rate limit)
    Route::post('contact', [V2\ContactController::class, 'store'])
        ->middleware('throttle:30,1');

    // Authenticated admin routes
    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        Route::post('sections', [V2\SectionController::class, 'store']);
        Route::put('sections/{id}', [V2\SectionController::class, 'update']);
        Route::delete('sections/{id}', [V2\SectionController::class, 'destroy']);
    });
});
