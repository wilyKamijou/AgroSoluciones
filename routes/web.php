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

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();



// Rutas protegidas por autenticación y roles
Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'home']);

    Route::middleware('auth', 'role:Owner,Gerente')->group(function () {
        // Rutas para gestión de usuarios
        Route::get('/user', [UsersController::class, 'index'])->name('user.index');
        Route::get('/user/crear', [UsersController::class, 'create'])->name('user.create');
        Route::post('/user/guardar', [UsersController::class, 'store'])->name('user.store');
        Route::get('/user/{id}/editar', [UsersController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}/actualizar', [UsersController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/eliminar', [UsersController::class, 'destroy'])->name('user.destroy');


        //modulo de usuario
        route::get('/mUser', [ModuloUsersController::class, 'index'])->name('user.index');
        route::post('/mUser/guardar', [ModuloUsersController::class, 'store'])->name('user.store');
        //rutas de empleado
        route::get('/empleado', [EmpleadoController::class, 'index']);
        route::get('/empleado/crear', [EmpleadoController::class, 'create']);
        route::post('/empleado/guardar', [EmpleadoController::class, 'store']);
        route::get('/empleado/{id}/editar', [EmpleadoController::class, 'edit']);
        route::Put('/empleado/{id}/actualizar', [EmpleadoController::class, 'update']);
        route::delete('/empleado/{id}/eliminar', [EmpleadoController::class, 'destroy']);
        Route::get('/empleado/pdf', [EmpleadoController::class, 'downloadPDF'])->name('empleado.pdf');
    });

    //pagina web
    Route::get('/pagina', [PaginaController::class, 'index']);
    Route::post('/enviar', [EnviarController::class, 'enviar']);

    // Rutas para gestión de cuenta
    Route::get('/mi-cuenta/perfil', [App\Http\Controllers\CuentaController::class, 'perfil'])->name('cuenta.perfil');
    Route::get('/mi-cuenta/cambiar-password', [App\Http\Controllers\CuentaController::class, 'cambiarPassword'])->name('cuenta.password');
    Route::post('/mi-cuenta/actualizar-password', [App\Http\Controllers\CuentaController::class, 'actualizarPassword'])->name('cuenta.actualizar-password');


    Route::middleware('auth', 'role:Owner,Encargado Ventas')->group(function () {
        //rutas de venta
        route::get('/venta', [VentaController::class, 'index']);
        route::get('/venta/crear', [VentaController::class, 'create']);
        route::post('/venta/guardar', [VentaController::class, 'store']);
        route::get('/venta/{id}/editar', [VentaController::class, 'edit']);
        route::Put('/venta/{id}/actualizar', [VentaController::class, 'update']);
        route::delete('/venta/{id}/eliminar', [VentaController::class, 'destroy']);
        Route::get('/venta/pdf', [VentaController::class, 'downloadPDF'])->name('venta.pdf');
        //modulo de ventas 
        Route::get('/mVenta/buscar', [ModuloVentaController::class, 'buscar']);
        Route::get('/mVenta/buscarA', [ModuloVentaController::class, 'buscarA']);
        //Route::get('/mVenta/verificar', [ModuloVentaController::class, 'verificar']);
        route::get('/mVenta', [ModuloVentaController::class, 'index']);
        route::post('/mVenta/guardar', [ModuloVentaController::class, 'store']);
        //rutas para detalle venta
        route::get('/detalleVe', [DetalleVentaController::class, 'index']);
        route::get('/detalleVe/crear', [DetalleVentaController::class, 'create']);
        route::post('/detalleVe/guardar', [DetalleVentaController::class, 'store']);
        route::get('/detalleVe/{id1}/{id2}/{id3}/editar', [DetalleVentaController::class, 'edit']);
        route::Put('/detalleVe/{id1}/{id2}/{id3}/actualizar', [DetalleVentaController::class, 'update']);
        route::delete('/detalleVe/{id1}/{id2}/{id3}/eliminar', [DetalleVentaController::class, 'destroy']);
        Route::get('/detalleVe/pdf', [DetalleVentaController::class, 'downloadPDF'])->name('detalleVenta.pdf');
        Route::get('/api/producto/{id}/precio', function ($id) {
            $producto = App\Models\producto::find($id);

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
        });
    });

    Route::middleware('auth', 'role:Owner,Gerente')->group(function () {
        //rutas de cliente
        route::get('/cliente', [ClienteController::class, 'index']);
        route::get('/cliente/crear', [ClienteController::class, 'create']);
        route::post('/cliente/guardar', [ClienteController::class, 'store']);
        route::get('/cliente/{id}/editar', [ClienteController::class, 'edit']);
        route::Put('/cliente/{id}/actualizar', [ClienteController::class, 'update']);
        route::delete('/cliente/{id}/eliminar', [ClienteController::class, 'destroy']);
        //rutas para tipos de empleado
        route::get('/tipo', [TipoEmpleadoController::class, 'index']);
        route::get('/tipo/crear', [TipoEmpleadoController::class, 'create']);
        route::post('/tipo/guardar', [TipoEmpleadoController::class, 'store']);
        route::get('/tipo/{id}/editar', [TipoEmpleadoController::class, 'edit']);
        route::Put('/tipo/{id}/actualizar', [TipoEmpleadoController::class, 'update']);
        route::delete('/tipo/{id}/eliminar', [TipoEmpleadoController::class, 'destroy']);
    });

    Route::middleware('auth', 'role:Owner,Encargado Almacenes')->group(function () {
        //rutas para almacen
        route::get('/almacen', [AlmacenController::class, 'index']);
        route::get('/almacen/crear', [AlmacenController::class, 'create']);
        route::post('/almacen/guardar', [AlmacenController::class, 'store']);
        route::get('/almacen/{id}/editar', [AlmacenController::class, 'edit']);
        route::Put('/almacen/{id}/actualizar', [AlmacenController::class, 'update']);
        route::delete('/almacen/{id}/eliminar', [AlmacenController::class, 'destroy']);
        Route::get('/almacen/pdf', [AlmacenController::class, 'downloadPDF'])->name('almacen.pdf');
        //modulo de inventario
        route::get('/mAlmacen', [ModuloAlmacenController::class, 'index'])->name('home');
        route::post('/mAlmacen/guardar', [ModuloAlmacenController::class, 'store'])->name('home');
        //rutas para producto
        route::get('/producto', [ProductoController::class, 'index']);
        route::get('/producto/crear', [ProductoController::class, 'create']);
        route::post('/producto/guardar', [ProductoController::class, 'store']);
        route::get('/producto/{id}/editar', [ProductoController::class, 'edit']);
        route::Put('/producto/{id}/actualizar', [ProductoController::class, 'update']);
        route::delete('/producto/{id}/eliminar', [ProductoController::class, 'destroy']);
        Route::get('/producto/pdf', [ProductoController::class, 'downloadPDF'])->name('producto.pdf');
        //rutas para detalle almacen
        route::get('/detalleAl', [DetalleAlmacenController::class, 'index']);
        route::get('/detalleAl/crear', [DetalleAlmacenController::class, 'create']);
        route::post('/detalleAl/guardar', [DetalleAlmacenController::class, 'store']);
        route::get('/detalleAl/{id1}/{id2}/editar', [DetalleAlmacenController::class, 'edit']);
        route::Put('/detalleAl/{id1}/{id2}/actualizar', [DetalleAlmacenController::class, 'update']);
        route::delete('/detalleAl/{id1}/{id2}/eliminar', [DetalleAlmacenController::class, 'destroy']);
        Route::get('/detalleAl/pdf', [DetalleAlmacenController::class, 'downloadPDF'])->name('detalleAlmacen.pdf');
        //rutas para categoria de productos
        route::get('/categoria', [CategoriaController::class, 'index']);
        route::get('/categoria/crear', [CategoriaController::class, 'create']);
        route::post('/categoria/guardar', [CategoriaController::class, 'store']);
        route::get('/categoria/{id}/editar', [CategoriaController::class, 'edit']);
        route::Put('/categoria/{id}/actualizar', [CategoriaController::class, 'update']);
        route::delete('/categoria/{id}/eliminar', [CategoriaController::class, 'destroy']);
    });



    Route::middleware('auth', 'role:Owner')->group(function () {
        // Rutas para poblar datos
        Route::prefix('poblacion')->group(function () {
            Route::get('/', [DataPoblacionController::class, 'index'])->name('poblacion.index');
            Route::post('/tipo-empleado', [DataPoblacionController::class, 'poblarTipoEmpleado'])->name('poblacion.tipo-empleado');
            Route::post('/usuarios', [DataPoblacionController::class, 'poblarUsuarios'])->name('poblacion.usuarios');
            Route::post('/empleado', [DataPoblacionController::class, 'poblarEmpleado'])->name('poblacion.empleado');
            Route::post('/cliente', [DataPoblacionController::class, 'poblarCliente'])->name('poblacion.cliente');
            Route::post('/todo', [DataPoblacionController::class, 'poblarTodo'])->name('poblacion.todo');
            Route::post('/limpiar', [DataPoblacionController::class, 'limpiarDatos'])->name('poblacion.limpiar');
            Route::post('/categorias', [DataPoblacionController::class, 'poblarCategorias'])->name('poblacion.categorias');
            Route::post('/productos', [DataPoblacionController::class, 'poblarProductos'])->name('poblacion.productos');
            Route::post('/almacenes', [DataPoblacionController::class, 'poblarAlmacenes'])->name('poblacion.almacenes');
            Route::post('/detalle-almacen', [DataPoblacionController::class, 'poblarDetalleAlmacen'])->name('poblacion.detalle-almacen');
            Route::post('/ventas', [DataPoblacionController::class, 'poblarVentas'])->name('poblacion.ventas');
            Route::post('/detalle-venta', [DataPoblacionController::class, 'poblarDetalleVenta'])->name('poblacion.detalle-venta');
        });
    });



    // Ruta para la vista principal de reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // Rutas para las APIs de reportes
    Route::prefix('api/reportes')->group(function () {
        Route::get('/productos-mas-vendidos-cantidad', [ReporteController::class, 'productosMasVendidosPorCantidad']);
        Route::get('/productos-mas-vendidos-monto', [ReporteController::class, 'productosMasVendidosPorMonto']);
        Route::get('/distribucion-almacenes', [ReporteController::class, 'productosEnAlmacenes']);
        Route::get('/empleados-top-ventas', [ReporteController::class, 'empleadoConMasVentas']);
        Route::get('/completo', [ReporteController::class, 'reporteCompleto']);
    });
});
