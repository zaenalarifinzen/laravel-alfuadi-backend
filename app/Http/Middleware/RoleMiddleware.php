<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Administrator verification.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (! $user) {
            // Not Login
            abort(403, 'Anda belum login.');
        }

        if (! in_array($user->roles, $roles)) {
            abort(403, "Anda tidak memiliki akses ke halaman ini.");
        }

        return $next($request);
    }
}
