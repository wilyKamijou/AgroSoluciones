<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
 public function boot()
    {
        // Inyectar menú dinámico después de la autenticación
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $menu = $this->generarMenuSegunRol();
                $view->with('menuDinamico', $menu);
                
                // También sobrescribir menú de AdminLTE
                config(['adminlte.menu' => $menu]);
            }
        });
    }

    private function generarMenuSegunRol()
    {
        $user = Auth::user();
        
        // Si no hay usuario autenticado, menú vacío
        if (!$user || !$user->empleado || !$user->empleado->tipoEmpleado) {
            return [];
        }

        $rol = $user->empleado->tipoEmpleado->nombreE;
        
        $menuBase = [
            // Navbar items
            [
                'type' => 'navbar-search',
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            
            // Sidebar search
            [
                'type' => 'sidebar-menu-search',
                'text' => 'search',
            ],
        ];

        // ===== AGREGAR ITEMS SEGÚN ROL =====

        // Dashboard
        if (in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes'])) {
            $menuBase[] = [
                'text' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fas fa-home',
                'label_color' => 'success',
            ];
        }

        // Usuarios - Solo Owner y Gerente
        if (in_array($rol, ['Owner', 'Gerente'])) {
            $menuBase[] = [
                'text' => 'Usuarios',
                'icon' => 'far fa-fw fa-user',
                'submenu' => [
                    [
                        'text' => 'Modulo de Usuarios',
                        'url' => '/mUser',
                    ],
                    [
                        'text' => 'Cuentas',
                        'url' => '/user',
                    ]
                ],
                'label_color' => 'success',
            ];
        }

        // Clientes - Owner, Gerente, Encargado Ventas
        if (in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas'])) {
            $menuBase[] = [
                'text' => 'Clientes',
                'url' => '/cliente',
                'icon' => 'fas fa-users',
                'label_color' => 'success',
            ];
        }

        // Empleados - Solo Owner y Gerente
        if (in_array($rol, ['Owner', 'Gerente'])) {
            $menuBase[] = [
                'text' => 'Empleados',
                'icon' => 'far fa-address-book',
                'submenu' => [
                    [
                        'text' => 'Empleados',
                        'url' => '/empleado',
                    ],
                    [
                        'text' => 'Tipos de empleados',
                        'url' => '/tipo',
                    ]
                ],
                'label_color' => 'success',
            ];
        }

        // Ventas - Owner, Gerente, Encargado Ventas
        if (in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas'])) {
            $menuBase[] = [
                'text' => 'Ventas',
                'icon' => 'far fa-money-bill-alt',
                'submenu' => [
                    [
                        'text' => 'Modulo de Ventas',
                        'url' => '/mVenta',
                    ],
                    [
                        'text' => 'Ventas',
                        'url' => '/venta',
                    ],
                    [
                        'text' => 'Detalle de las ventas',
                        'url' => '/detalleVe',
                    ]
                ],
                'label_color' => 'success',
            ];
        }

        // Almacenes - Owner, Gerente, Encargado Almacenes
        if (in_array($rol, ['Owner', 'Gerente', 'Encargado Almacenes'])) {
            $menuBase[] = [
                'text' => 'Almacenes',
                'icon' => 'far fa-building',
                'submenu' => [
                    [
                        'text' => 'Modulo de inventario',
                        'url' => '/mAlmacen',
                    ],
                    [
                        'text' => 'Almacenes',
                        'url' => '/almacen',
                    ],
                    [
                        'text' => 'Detalle de los almacenes',
                        'url' => '/detalleAl',
                    ]
                ],
                'label_color' => 'success',
            ];
        }

        // Productos - Todos excepto ?
        if (in_array($rol, ['Owner', 'Gerente', 'Encargado Ventas', 'Encargado Almacenes'])) {
            $menuBase[] = [
                'text' => 'Productos',
                'icon' => 'fas fa-flask',
                'submenu' => [
                    [
                        'text' => 'Productos',
                        'url' => '/producto',
                    ],
                    [
                        'text' => 'Categorias de productos',
                        'url' => '/categoria',
                    ]
                ],
                'label_color' => 'success',
            ];
        }

        // Poblacion de datos - Solo Owner y Gerente
        if (in_array($rol, ['Owner', 'Gerente'])) {
            $menuBase[] = [
                'text' => 'Poblacion de datos',
                'url'  => '/poblacion',
                'icon' => 'fas fa-database',
                'label_color' => 'success',
            ];
        }

        // Ajustes de cuenta - Todos los usuarios autenticados
        $menuBase[] = ['header' => 'Ajustes de la cuenta'];
        $menuBase[] = [
            'text' => 'Mi Perfil',
            'url' => '/mi-cuenta/perfil',
            'icon' => 'fas fa-fw fa-user',
        ];
        $menuBase[] = [
            'text' => 'Cambiar Contraseña',
            'url' => '/mi-cuenta/cambiar-password',
            'icon' => 'fas fa-fw fa-lock',
        ];

        return $menuBase;
    }
}
