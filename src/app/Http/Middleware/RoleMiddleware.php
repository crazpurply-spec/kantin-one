<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super Admin memiliki seluruh akses Administrator.
        if ($user->isSuperAdmin() && in_array('administrator', $roles, true)) {
            return $next($request);
        }

        // Pemeriksaan role.
        if (! in_array($user->role, $roles, true)) {

            // Redirect berdasarkan role pengguna saat ini.
            $dashboard = match ($user->role) {
                'super_admin', 'administrator' => 'dashboard',
                'canteen_manager' => 'dashboard.canteen_manager',
                'tenant', 'tenant_employee' => 'dashboard.tenant',

                default => 'login',
            };

            return redirect()->route($dashboard);
        }

        return $next($request);
    }
}