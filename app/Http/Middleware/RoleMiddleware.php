<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificamos si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Verificamos si el rol del usuario coincide con el requerido
        if (Auth::user()->role !== $role) {
            // Si no coincide, podemos redirigir a una página de error o al dashboard por defecto.
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // 3. Si todo está bien, permitimos el acceso
        return $next($request);
    }
}
