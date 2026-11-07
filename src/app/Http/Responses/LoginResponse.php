<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $route = match ($request->user()->role) {
            'super_admin', 'administrator' => 'dashboard',
            'canteen_manager' => 'dashboard.canteen_manager',
            'tenant', 'tenant_employee' => 'dashboard.tenant',

            default => abort(403, 'Role akun belum memiliki dashboard.'),
        };

        if ($request->wantsJson()) {
            return new JsonResponse([
                'redirect' => route($route),
            ]);
        }

        return redirect()->route($route);
    }
}