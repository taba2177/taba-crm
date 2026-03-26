<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Taba\Crm\Http\Requests\Api\LoginRequest;
use Taba\Crm\Http\Requests\Api\RegisterRequest;
use Taba\Crm\Http\Resources\Api\UserResource;
use Taba\Crm\Models\User;

class AuthApiController extends ApiController
{
    /**
     * Register a new user and return a token.
     *
     * POST /api/v1/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken(
            'api-token',
            ['*'],
            now()->addDays(config('crm.api.token_expiration_days', 30))
        )->plainTextToken;

        return $this->created([
            'user'  => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registration successful');
    }

    /**
     * Authenticate user and return a token.
     *
     * POST /api/v1/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        // Revoke previous tokens if configured
        if (config('crm.api.single_session', false)) {
            $user->tokens()->delete();
        }

        $token = $user->createToken(
            'api-token',
            ['*'],
            now()->addDays(config('crm.api.token_expiration_days', 30))
        )->plainTextToken;

        return $this->success([
            'user'  => new UserResource($user->load('roles')),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Revoke the current token (logout).
     *
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    /**
     * Get the authenticated user's profile.
     *
     * GET /api/v1/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success(
            new UserResource($request->user()->load('roles'))
        );
    }

    /**
     * Update the authenticated user's profile.
     *
     * PUT /api/v1/auth/me
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return $this->success(new UserResource($user->fresh()->load('roles')), 'Profile updated');
    }

    /**
     * Change password.
     *
     * POST /api/v1/auth/change-password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->error('Current password is incorrect', 422);
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        // Optionally revoke all tokens after password change
        $user->tokens()->delete();

        $token = $user->createToken(
            'api-token',
            ['*'],
            now()->addDays(config('crm.api.token_expiration_days', 30))
        )->plainTextToken;

        return $this->success([
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 'Password changed successfully');
    }

    /**
     * Refresh the token (revoke current, issue new).
     *
     * POST /api/v1/auth/refresh
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke current token
        $user->currentAccessToken()->delete();

        $token = $user->createToken(
            'api-token',
            ['*'],
            now()->addDays(config('crm.api.token_expiration_days', 30))
        )->plainTextToken;

        return $this->success([
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 'Token refreshed');
    }
}
