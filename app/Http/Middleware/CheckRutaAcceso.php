<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class CheckRutaAcceso
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next, ...$iniciales)
    {
        $user = Auth::user();
        
        // Si no hay usuario autenticado
        if (!$user) {
            return abort(403, 'Acceso no autorizado. Usuario no autenticado.');
        }
        
        // Si no tiene relación con empleado
        if (!$user->empleado || !$user->empleado->tipoEmpleado) {
            return abort(403, 'Acceso no autorizado. Información de empleado incompleta.');
        }
        
        // Obtener rutas de acceso del usuario
        $rutasAcceso = $user->empleado->tipoEmpleado->rutas_acceso ?? '';
        
        // Convertir a array de iniciales
        $rutasAcceso = trim($rutasAcceso);
        $inicialesUsuario = !empty($rutasAcceso) ? explode(' ', $rutasAcceso) : [];
        $inicialesUsuario = array_filter($inicialesUsuario); // Limpiar vacíos
        
        // DEBUG: Ver qué está pasando
        \Log::info('Verificación de rutas', [
            'usuario' => $user->email,
            'rutas_acceso_bd' => $rutasAcceso,
            'iniciales_usuario' => $inicialesUsuario,
            'iniciales_requeridas' => $iniciales,
            'ruta' => $request->path()
        ]);
        
        // Verificar OR: al menos una inicial coincidente
        // Si $iniciales está vacío, permite acceso (para rutas sin restricciones específicas)
        if (!empty($iniciales)) {
            $coincidencias = array_intersect($iniciales, $inicialesUsuario);
            
            if (empty($coincidencias)) {
                \Log::warning('Acceso denegado', [
                    'usuario' => $user->email,
                    'rutas_usuario' => $inicialesUsuario,
                    'rutas_requeridas' => $iniciales,
                    'ruta_solicitada' => $request->path()
                ]);
                
                return abort(403, 'No tienes permiso para acceder a esta sección. Se requiere al menos uno de: ' . implode(', ', $iniciales));
            }
        }
        
        return $next($request);
    }

}