<?php

namespace App\Http\Middleware;

use App\Enums\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var Roles $userRole */
        $userRole = auth()->user()->role; // Enum Roles

        // Converter nomes passados na rota para Enum Roles
        $allowedRoles = array_map(function ($roleName) {
            return Roles::fromName($roleName);
        }, $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
