<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TipoEmpleadoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetalleAlmacenController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\UsersController;
use GuzzleHttp\Client;
use App\Http\Controllers\DataPoblacionController;
use App\Http\Controllers\EnviarController;
use App\Http\Controllers\ModuloAlmacenController;
use App\Http\Controllers\ModuloUsersController;
use App\Http\Controllers\ModuloVentaController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ContactoController;

Route::get('/', function () {
    return redirect()->route('inicio');
});
//pagina web
Route::get('/pagina', [PaginaController::class, 'index'])->name('inicio');
Route::post('/enviar', [EnviarController::class, 'enviar']);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviarMensaje'])->name('contacto.enviar');

Auth::routes();

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
        
        // ==================== RUTAS PÚBLICAS PARA TODOS LOS USUARIOS AUTENTICADOS ====================
        
        // Dashboard/Home
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        
        // Rutas para gestión de cuenta personal (todos los usuarios)
        Route::prefix('mi-cuenta')->group(function () {
            Route::get('/perfil', [App\Http\Controllers\CuentaController::class, 'perfil'])->name('cuenta.perfil');
            Route::get('/cambiar-password', [App\Http\Controllers\CuentaController::class, 'cambiarPassword'])->name('cuenta.password');
            Route::post('/actualizar-password', [App\Http\Controllers\CuentaController::class, 'actualizarPassword'])->name('cuenta.actualizar-password');
        });

        // ==================== RUTAS ESPECÍFICAS POR PERMISOS (RUVECAP) ====================
// ===== R: REPORTES =====
Route::middleware('ruta.acceso:R')->group(function () {
    // Ruta para la vista principal de reportes
    Route::get('/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');
    
    // APIs de reportes
    Route::prefix('api/reportes')->group(function () {
        Route::get('/productos-mas-vendidos-cantidad', [App\Http\Controllers\ReporteController::class, 'productosMasVendidosPorCantidad']);
        Route::get('/productos-mas-vendidos-monto', [App\Http\Controllers\ReporteController::class, 'productosMasVendidosPorMonto']);
        Route::get('/distribucion-almacenes', [App\Http\Controllers\ReporteController::class, 'productosEnAlmacenes']);
        Route::get('/empleados-top-ventas', [App\Http\Controllers\ReporteController::class, 'empleadoConMasVentas']);
        Route::get('/completo', [App\Http\Controllers\ReporteController::class, 'reporteCompleto']);
        // Nueva ruta simple para vencimientos (eliminado el método antiguo)
        Route::get('/productos-vencimiento', [ReporteController::class, 'productosPorVencerSimple']);
    });
    
    // Exportaciones
    Route::prefix('reportes/exportar')->group(function () {
        Route::get('/pdf/{tipo}', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar.pdf');
        Route::get('/excel/{tipo}', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');
    });
});
        
    // ===== U: USUARIOS =====
Route::middleware('ruta.acceso:U')->group(function () {
    // Módulo de usuarios (URL original: /mUser)
    Route::get('/mUser', [App\Http\Controllers\ModuloUsersController::class, 'index'])->name('user.index');
    Route::post('/mUser/guardar', [App\Http\Controllers\ModuloUsersController::class, 'store'])->name('user.store');
    
    // Gestión de cuentas (URL original: /user)
    Route::prefix('user')->group(function () {
        Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('user.index');
        Route::get('/crear', [App\Http\Controllers\UsersController::class, 'create'])->name('user.create');
        Route::post('/guardar', [App\Http\Controllers\UsersController::class, 'store'])->name('user.store');
        Route::get('/{id}/editar', [App\Http\Controllers\UsersController::class, 'edit'])->name('user.edit');
        Route::put('/{id}/actualizar', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');
        Route::delete('/{id}/eliminar', [App\Http\Controllers\UsersController::class, 'destroy'])->name('user.destroy');
    });
    // Rutas para autocompletado de usuarios
    Route::get('/mUser/buscar-empleados', [ModuloUsersController::class, 'buscarEmpleados'])
        ->name('users.buscar.empleados');
        
    Route::get('/mUser/buscar-tipos', [ModuloUsersController::class, 'buscarTiposEmpleado'])
        ->name('users.buscar.tipos');
        
    Route::get('/mUser/buscar-usuarios', [ModuloUsersController::class, 'buscarUsuarios'])
        ->name('users.buscar.usuarios');

    // Ruta para guardar usuario
    Route::post('/mUser/guardar', [ModuloUsersController::class, 'store'])
        ->name('mUser.guardar');

    // Ruta principal (si no existe)
    Route::get('/mUser', [ModuloUsersController::class, 'index'])
        ->name('mUser.index');
});

// ===== V: VENTAS =====
Route::middleware('ruta.acceso:V')->group(function () {
    // Módulo de ventas (URL original: /mVenta)
    Route::get('/mVenta', [App\Http\Controllers\ModuloVentaController::class, 'index']);
    Route::post('/mVenta/guardar', [App\Http\Controllers\ModuloVentaController::class, 'store']);
    Route::get('/mVenta/buscar', [App\Http\Controllers\ModuloVentaController::class, 'buscar']);
    Route::get('/mVenta/buscarA', [App\Http\Controllers\ModuloVentaController::class, 'buscarA']);
    
    // Gestión de ventas (URL original: /venta)
    Route::prefix('venta')->group(function () {
        Route::get('/', [App\Http\Controllers\VentaController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\VentaController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\VentaController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\VentaController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\VentaController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\VentaController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\VentaController::class, 'downloadPDF'])->name('venta.pdf');
    });
    
    // Detalle de ventas (URL original: /detalleVe)
    Route::prefix('detalleVe')->group(function () {
        Route::get('/', [App\Http\Controllers\DetalleVentaController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\DetalleVentaController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\DetalleVentaController::class, 'store']);
        Route::get('/{id1}/{id2}/{id3}/editar', [App\Http\Controllers\DetalleVentaController::class, 'edit']);
        Route::put('/{id1}/{id2}/{id3}/actualizar', [App\Http\Controllers\DetalleVentaController::class, 'update']);
        Route::delete('/{id1}/{id2}/{id3}/eliminar', [App\Http\Controllers\DetalleVentaController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\DetalleVentaController::class, 'downloadPDF'])->name('detalleVenta.pdf');
    });
    
    // API para obtener precio de producto
    Route::get('/api/producto/{id}/precio', function ($id) {
        $producto = App\Models\Producto::find($id);
        
        if ($producto) {
            return response()->json([
                'success' => true,
                'precio' => $producto->precioPr
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado'
        ], 404);
    })->name('api.producto.precio');
});

// ===== C: CLIENTES =====
Route::middleware('ruta.acceso:C')->group(function () {
    // Clientes (URL original: /cliente)
    Route::prefix('cliente')->group(function () {
        Route::get('/', [App\Http\Controllers\ClienteController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\ClienteController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\ClienteController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\ClienteController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\ClienteController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\ClienteController::class, 'destroy']);
    });
});

// ===== E: EMPLEADOS =====
Route::middleware('ruta.acceso:E')->group(function () {
    // Empleados (URL original: /empleado)
    Route::prefix('empleado')->group(function () {
        Route::get('/', [App\Http\Controllers\EmpleadoController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\EmpleadoController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\EmpleadoController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\EmpleadoController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\EmpleadoController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\EmpleadoController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\EmpleadoController::class, 'downloadPDF'])->name('empleado.pdf');
    });
    
    // Tipos de empleado (URL original: /tipo)
    Route::prefix('tipo')->group(function () {
        Route::get('/', [App\Http\Controllers\TipoEmpleadoController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\TipoEmpleadoController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\TipoEmpleadoController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\TipoEmpleadoController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\TipoEmpleadoController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\TipoEmpleadoController::class, 'destroy']);
    });
});

// ===== A: ALMACENES =====
Route::middleware('ruta.acceso:A')->group(function () {
    // Módulo de inventario (URL original: /mAlmacen)
    Route::get('/mAlmacen', [App\Http\Controllers\ModuloAlmacenController::class, 'index']);
    Route::post('/mAlmacen/guardar', [App\Http\Controllers\ModuloAlmacenController::class, 'store']);
    
    // Almacenes (URL original: /almacen)
    Route::prefix('almacen')->group(function () {
        Route::get('/', [App\Http\Controllers\AlmacenController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\AlmacenController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\AlmacenController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\AlmacenController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\AlmacenController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\AlmacenController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\AlmacenController::class, 'downloadPDF'])->name('almacen.pdf');
    });
   // Rutas para autocompletado de almacén - DEBEN ser iguales a las de ventas
    Route::get('/mAlmacen/buscar-productos', [ModuloAlmacenController::class, 'buscarProductos'])
        ->name('almacen.buscar.productos');
        
    Route::get('/mAlmacen/buscar-categorias', [ModuloAlmacenController::class, 'buscarCategorias'])
        ->name('almacen.buscar.categorias');
        
    Route::get('/mAlmacen/buscar-almacenes', [ModuloAlmacenController::class, 'buscarAlmacenes'])
        ->name('almacen.buscar.almacenes');

    // Ruta para guardar
    Route::post('/mAlmacen/guardar', [ModuloAlmacenController::class, 'store'])
        ->name('mAlmacen.guardar');

    // Ruta principal (si no existe)
    Route::get('/mAlmacen', [ModuloAlmacenController::class, 'index'])
        ->name('mAlmacen.index');
            
    // Productos (URL original: /producto)
    Route::prefix('producto')->group(function () {
        Route::get('/', [App\Http\Controllers\ProductoController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\ProductoController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\ProductoController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\ProductoController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\ProductoController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\ProductoController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\ProductoController::class, 'downloadPDF'])->name('producto.pdf');
    });
    
    // Detalle almacenes (URL original: /detalleAl)
    Route::prefix('detalleAl')->group(function () {
        Route::get('/', [App\Http\Controllers\DetalleAlmacenController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\DetalleAlmacenController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\DetalleAlmacenController::class, 'store']);
        Route::get('/{id1}/{id2}/editar', [App\Http\Controllers\DetalleAlmacenController::class, 'edit']);
        Route::put('/{id1}/{id2}/actualizar', [App\Http\Controllers\DetalleAlmacenController::class, 'update']);
        Route::delete('/{id1}/{id2}/eliminar', [App\Http\Controllers\DetalleAlmacenController::class, 'destroy']);
        Route::get('/pdf', [App\Http\Controllers\DetalleAlmacenController::class, 'downloadPDF'])->name('detalleAlmacen.pdf');
    });
    
    // Categorías (URL original: /categoria)
    Route::prefix('categoria')->group(function () {
        Route::get('/', [App\Http\Controllers\CategoriaController::class, 'index']);
        Route::get('/crear', [App\Http\Controllers\CategoriaController::class, 'create']);
        Route::post('/guardar', [App\Http\Controllers\CategoriaController::class, 'store']);
        Route::get('/{id}/editar', [App\Http\Controllers\CategoriaController::class, 'edit']);
        Route::put('/{id}/actualizar', [App\Http\Controllers\CategoriaController::class, 'update']);
        Route::delete('/{id}/eliminar', [App\Http\Controllers\CategoriaController::class, 'destroy']);
    });
});

// ===== P: PRODUCTOS =====
Route::middleware('ruta.acceso:P')->group(function () {
    // Población de datos (URL original: /poblacion)
    Route::prefix('poblacion')->group(function () {
        Route::get('/', [App\Http\Controllers\DataPoblacionController::class, 'index'])->name('poblacion.index');
        Route::post('/tipo-empleado', [App\Http\Controllers\DataPoblacionController::class, 'poblarTipoEmpleado'])->name('poblacion.tipo-empleado');
        Route::post('/usuarios', [App\Http\Controllers\DataPoblacionController::class, 'poblarUsuarios'])->name('poblacion.usuarios');
        Route::post('/empleado', [App\Http\Controllers\DataPoblacionController::class, 'poblarEmpleado'])->name('poblacion.empleado');
        Route::post('/cliente', [App\Http\Controllers\DataPoblacionController::class, 'poblarCliente'])->name('poblacion.cliente');
        Route::post('/todo', [App\Http\Controllers\DataPoblacionController::class, 'poblarTodo'])->name('poblacion.todo');
        Route::post('/limpiar', [App\Http\Controllers\DataPoblacionController::class, 'limpiarDatos'])->name('poblacion.limpiar');
        Route::post('/categorias', [App\Http\Controllers\DataPoblacionController::class, 'poblarCategorias'])->name('poblacion.categorias');
        Route::post('/productos', [App\Http\Controllers\DataPoblacionController::class, 'poblarProductos'])->name('poblacion.productos');
        Route::post('/almacenes', [App\Http\Controllers\DataPoblacionController::class, 'poblarAlmacenes'])->name('poblacion.almacenes');
        Route::post('/detalle-almacen', [App\Http\Controllers\DataPoblacionController::class, 'poblarDetalleAlmacen'])->name('poblacion.detalle-almacen');
        Route::post('/ventas', [App\Http\Controllers\DataPoblacionController::class, 'poblarVentas'])->name('poblacion.ventas');
        Route::post('/detalle-venta', [App\Http\Controllers\DataPoblacionController::class, 'poblarDetalleVenta'])->name('poblacion.detalle-venta');
    });
});
    
});