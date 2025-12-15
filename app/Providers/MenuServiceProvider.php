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
                $menu = $this->generarMenuSegunRutas();
                $view->with('menuDinamico', $menu);
                
                // También sobrescribir menú de AdminLTE
                config(['adminlte.menu' => $menu]);
            }
        });
    }

   private function generarMenuSegunRutas()
    {
        $user = Auth::user();
        
            // Si no hay usuario autenticado, menú vacío
        if (!$user || !$user->empleado || !$user->empleado->tipoEmpleado) {
            return [];
        }


            // Obtener las rutas de acceso del tipo de empleado
        $rutasAcceso = $user->empleado->tipoEmpleado->rutas_acceso ?? '';
            
            // Convertir a array de iniciales
        $rutasAcceso = trim($rutasAcceso);
        $iniciales = !empty($rutasAcceso) ? explode(' ', $rutasAcceso) : [];

        $iniciales = array_filter($iniciales);
    

        
        // También puedes obtener el rol si necesitas
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

        // ===== AGREGAR ITEMS SEGÚN INICIALES DE RUTAS =====

        // Dashboard/Home siempre visible
        $menuBase[] = [
            'text' => 'Dashboard',
            'url' => '/home',
            'icon' => 'fas fa-tachometer-alt',
            'label_color' => 'success',
        ];

        // Reportes - Si tiene 'R'
        if (in_array('R', $iniciales)) {
            $menuBase[] = [
                'text' => 'Reportes',
                'url' => '/reportes',
                'icon' => 'fas fa-chart-bar',
                'label_color' => 'success',
            ];
        }

        // Usuarios - Si tiene 'U'
        if (in_array('U', $iniciales)) {
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

        // Clientes - Si tiene 'C'
        if (in_array('C', $iniciales)) {
            $menuBase[] = [
                'text' => 'Clientes',
                'url' => '/cliente',
                'icon' => 'fas fa-users',
                'label_color' => 'success',
            ];
        }

        // Empleados - Si tiene 'E'
        if (in_array('E', $iniciales)) {
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

        // Ventas - Si tiene 'V'
        if (in_array('V', $iniciales)) {
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

        // Almacenes - Si tiene 'A'
        if (in_array('A', $iniciales)) {
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

        // Productos - Si tiene 'P'
        if (in_array('P', $iniciales)) {
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

        // Poblacion de datos - Solo si tiene todas las rutas o rol específico
        // Puedes personalizar esta condición según tus necesidades
        if ($rol == 'Owner' || $rol == 'Gerente') {
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
