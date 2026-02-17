<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RefreshTokenRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'user' => $result['user'],
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated('email'),
            $request->validated('password')
        );

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'message' => $result['message'],
            'user' => $result['user'],
            'token' => $result['token'],
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $result = $this->authService->refreshToken();

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'message' => $result['message'],
            'token' => $result['token'],
        ]);
    }

    public function logout(): JsonResponse
    {
        $result = $this->authService->logout();

        return response()->json([
            'message' => $result['message'],
        ]);
    }

    public function me(): JsonResponse
    {
        $result = $this->authService->me();

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'user' => $result['user'],
        ]);
    }
}
