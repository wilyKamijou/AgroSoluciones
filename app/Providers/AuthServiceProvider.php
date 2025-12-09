<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Helpers\RolHelper;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        // ===== GATES PARA MOSTRAR EN MENÚ =====
        
        // Usuarios y cuentas
        Gate::define('ver-modulo-usuarios', function () {
            return RolHelper::puedeAcceder('usuarios');
        });
        
        Gate::define('ver-modulo-cuentas', function () {
            return RolHelper::puedeAcceder('usuarios');
        });

        // Clientes
        Gate::define('ver-modulo-clientes', function () {
            $rol = RolHelper::rolActual();
            return in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas']);
        });

        // Empleados
        Gate::define('ver-modulo-empleados', function () {
            return RolHelper::puedeAcceder('empleados');
        });
        
        Gate::define('ver-modulo-tipos-empleados', function () {
            return RolHelper::puedeAcceder('empleados');
        });

        // Ventas
        Gate::define('ver-modulo-ventas', function () {
            return RolHelper::puedeAcceder('ventas');
        });
        
        Gate::define('ver-modulo-detalle-ventas', function () {
            return RolHelper::puedeAcceder('ventas');
        });

        // Almacenes
        Gate::define('ver-modulo-almacenes', function () {
            return RolHelper::puedeAcceder('almacenes');
        });
        
        Gate::define('ver-modulo-detalle-almacenes', function () {
            return RolHelper::puedeAcceder('almacenes');
        });

        // Productos
        Gate::define('ver-modulo-productos', function () {
            $rol = RolHelper::rolActual();
            return in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes']);
        });
        
        Gate::define('ver-modulo-categorias', function () {
            $rol = RolHelper::rolActual();
            return in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes']);
        });

        // Población de datos
        Gate::define('ver-modulo-poblacion', function () {
            $rol = RolHelper::rolActual();
            return in_array($rol, ['Owner', 'Gerente']);
        });

        // Dashboard
        Gate::define('ver-dashboard', function () {
            $rol = RolHelper::rolActual();
            return in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes']);
        });
    }
}