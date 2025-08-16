<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckManagerPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si es manager
        if (Auth::guard('manager')->check()) {
            // Obtener la ruta actual
            $route = $request->route()->getName();
            
            // Rutas prohibidas para managers
            $restrictedRoutes = [
                'managers.create',
                'managers.store',
                'employee.destroy',
                'incomes.destroy',
            ];
            
            // Verificar si la ruta actual está restringida
            if (in_array($route, $restrictedRoutes)) {
                return redirect()->route('home')->with('error', 'No tienes permisos para realizar esta acción.');
            }
        }
        
        return $next($request);
    }
}
