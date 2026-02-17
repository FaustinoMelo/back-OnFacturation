<?php

namespace App\Modules\Auth\Services;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function register(array $data): array
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = JWTAuth::fromUser($user);

            return [
                'success' => true,
                'message' => 'Usuário registrado com sucesso.',
                'user' => $user->load('companies'),
                'token' => $token,
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao registrar usuário: ' . $e->getMessage(),
            ];
        }
    }

    public function login(string $email, string $password): array
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];

            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'success' => false,
                    'message' => 'Email ou senha inválidos.',
                ];
            }

            $user = auth('api')->user();

            return [
                'success' => true,
                'message' => 'Login realizado com sucesso.',
                'user' => $user->load('companies'),
                'token' => $token,
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao fazer login: ' . $e->getMessage(),
            ];
        }
    }

    public function refreshToken(): array
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return [
                'success' => true,
                'message' => 'Token renovado com sucesso.',
                'token' => $newToken,
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao renovar token: ' . $e->getMessage(),
            ];
        }
    }

    public function logout(): array
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return [
                'success' => true,
                'message' => 'Logout realizado com sucesso.',
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao fazer logout: ' . $e->getMessage(),
            ];
        }
    }

    public function me(): array
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Usuário não autenticado.',
                ];
            }

            return [
                'success' => true,
                'user' => $user->load('companies'),
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erro ao obter dados do usuário: ' . $e->getMessage(),
            ];
        }
    }
}
