<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
