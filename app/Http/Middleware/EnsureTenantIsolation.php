<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantScope;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsolation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $companyId = $user->active_company_id;

            if ($companyId) {
                TenantScope::setCompanyId($companyId);

                // Valida se usuário tem acesso à empresa
                $this->validateUserAccess($user, $companyId);
            }
        }

        return $next($request);
    }

    private function validateUserAccess($user, int $companyId): void
    {
        if (!$user->companies()->where('company_id', $companyId)->exists()) {
            abort(403, 'Acesso não autorizado a esta empresa');
        }
    }
}

