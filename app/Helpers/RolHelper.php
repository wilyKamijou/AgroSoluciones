<?php
// app/Helpers/RolHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RolHelper
{
    /**
     * Obtener el rol actual del usuario autenticado
     */
    
       public static function rol(): ?string
    {
        return self::rolActual();
    }   

    /**
     * Obtener el rol actual del usuario autenticado
     */
    public static function rolActual(): ?string
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        
        // Usar la misma lógica que el middleware
                if (!$user->Empleado || !$user->Empleado->tipoEmpleado) {
            return null;
        }

        return $user->Empleado->tipoEmpleado->nombreE;
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public static function tieneRol(string|array $roles): bool
    {
        $rolActual = self::rolActual();
        
        if (is_array($roles)) {
            return in_array($rolActual, $roles);
        }
        
        return $rolActual === $roles;
    }

    /**
     * Verificaciones rápidas por rol
     */
    public static function esOwner(): bool
    {
        return self::tieneRol('Owner');
    }

    public static function esGerente(): bool
    {
        return self::tieneRol('Gerente');
    }

    public static function esEncargadoVentas(): bool
    {
        return self::tieneRol('Encargado Ventas');
    }

    public static function esEncargadoAlmacenes(): bool
    {
        return self::tieneRol('Encargado Almacenes');
    }

    /**
     * Verificar si puede acceder a un módulo específico
     */
    public static function menu(): array
    {
        $rol = self::rolActual();
        
        $menuCompleto = [
            'dashboard' => [
                'icon' => 'fas fa-home', 
                'text' => 'Dashboard', 
                'ruta' => 'dashboard',
                'roles' => ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes']
            ],
            'empleados' => [
                'icon' => 'fas fa-users', 
                'text' => 'Empleados', 
                'ruta' => 'empleados.index',
                'roles' => ['Owner', 'Gerente']
            ],
            'ventas' => [
                'icon' => 'fas fa-shopping-cart', 
                'text' => 'Ventas', 
                'ruta' => 'ventas.index',
                'roles' => ['Owner', 'Gerente', 'Encargado Ventas']
            ],
            'almacenes' => [
                'icon' => 'fas fa-warehouse', 
                'text' => 'Almacenes', 
                'ruta' => 'almacenes.index',
                'roles' => ['Owner', 'Gerente', 'Encargado Almacenes']
            ],
            'reportes' => [
                'icon' => 'fas fa-chart-bar', 
                'text' => 'Reportes', 
                'ruta' => 'reportes.index',
                'roles' => ['Owner', 'Gerente']
            ],
            'configuracion' => [
                'icon' => 'fas fa-cog', 
                'text' => 'Configuración', 
                'ruta' => 'configuracion.index',
                'roles' => ['Owner']
            ],
        ];

        // Filtrar menú según rol
        $menuFiltrado = [];
        foreach ($menuCompleto as $key => $item) {
            if (in_array($rol, $item['roles'])) {
                $menuFiltrado[$key] = $item;
            }
        }

        return $menuFiltrado;
    }
    public static function puedeAcceder(string $modulo): bool
    {
        $rol = self::rolActual();
        
        // Owner puede acceder a todo
        if ($rol === 'Owner') {
            return true;
        }

        // Definir permisos por módulo
        $permisos = [
                    'usuarios' => ['Gerente'],
        'empleados' => ['Gerente'],
        'ventas' => ['Gerente', 'Encargado Ventas'],
        'almacenes' => ['Gerente', 'Encargado Almacenes'],
        'productos' => ['Gerente', 'Encargado Ventas', 'Encargado Almacenes'],
        'clientes' => ['Gerente', 'Encargado Ventas'],
        'poblacion' => ['Gerente'],
        'dashboard' => ['Gerente', 'Encargado Ventas', 'Encargado Almacenes']
        ];

        return isset($permisos[$modulo]) && in_array($rol, $permisos[$modulo]);
    }
}