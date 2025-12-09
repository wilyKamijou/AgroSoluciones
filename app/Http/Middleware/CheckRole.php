<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole  // ← IMPORTANTE: CheckRole, NO RolHelper
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // 2. Obtener rol del usuario
        // Usa Empleado (con E mayúscula según tu test)
        if (!$user->Empleado) {
            abort(403, 'Usuario no tiene empleado asociado');
        }
        
        // Usa tipoEmpleado (con t minúscula según tu test)
        if (!$user->Empleado->tipoEmpleado) {
            abort(403, 'Empleado no tiene tipo asignado');
        }
        
        // 3. Obtener el nombreE del tipoEmpleado
        $userRole = $user->Empleado->tipoEmpleado->nombreE;
        
        // 4. Depuración (opcional)
        // \Log::info('CheckRole', ['user' => $user->email, 'rol' => $userRole]);
        
        // 5. Si no hay roles especificados, permitir
        if (empty($roles)) {
            return $next($request);
        }
        
        // 6. Verificar si tiene alguno de los roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        
        // 7. Acceso denegado
        abort(403, 'Acceso denegado. Rol requerido: ' . implode(' o ', $roles) . '. Tu rol: ' . $userRole);
    }
}