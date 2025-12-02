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


Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();



Route::middleware('auth')->group(function () {


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'home']);

    //users
    route::get('/user', [UsersController::class, 'index'])->name('user.index');
    route::get('/user/crear', [UsersController::class, 'create'])->name('user.create');
    route::post('/user/guardar', [UsersController::class, 'store'])->name('user.store');
    route::get('/user/{id}/editar', [UsersController::class, 'edit'])->name('user.edit');
    route::Put('/user/{id}/actualizar', [UsersController::class, 'update'])->name('user.update');
    route::delete('/user/{id}/eliminar', [UsersController::class, 'destroy'])->name('user.destroy');
    //rutas de empleado
    route::get('/empleado', [EmpleadoController::class, 'index'])->name('home');
    route::get('/empleado/crear', [EmpleadoController::class, 'create'])->name('home');
    route::post('/empleado/guardar', [EmpleadoController::class, 'store'])->name('home');
    route::get('/empleado/{id}/editar', [EmpleadoController::class, 'edit'])->name('home');
    route::Put('/empleado/{id}/actualizar', [EmpleadoController::class, 'update'])->name('home');
    route::delete('/empleado/{id}/eliminar', [EmpleadoController::class, 'destroy'])->name('home');
    Route::get('/empleado/pdf', [EmpleadoController::class, 'downloadPDF'])->name('empleado.pdf');

    // Rutas para gestión de cuenta
    Route::get('/mi-cuenta/perfil', [App\Http\Controllers\CuentaController::class, 'perfil'])->name('cuenta.perfil');
    Route::get('/mi-cuenta/cambiar-password', [App\Http\Controllers\CuentaController::class, 'cambiarPassword'])->name('cuenta.password');
    Route::post('/mi-cuenta/actualizar-password', [App\Http\Controllers\CuentaController::class, 'actualizarPassword'])->name('cuenta.actualizar-password');
    //rutas de venta
    route::get('/venta', [VentaController::class, 'index'])->name('home');
    route::get('/venta/crear', [VentaController::class, 'create'])->name('home');
    route::post('/venta/guardar', [VentaController::class, 'store'])->name('home');
    route::get('/venta/{id}/editar', [VentaController::class, 'edit'])->name('home');
    route::Put('/venta/{id}/actualizar', [VentaController::class, 'update'])->name('home');
    route::delete('/venta/{id}/eliminar', [VentaController::class, 'destroy'])->name('home');
    Route::get('/venta/pdf', [VentaController::class, 'downloadPDF'])->name('venta.pdf');
    //rutas de cliente
    route::get('/cliente', [ClienteController::class, 'index'])->name('home');
    route::get('/cliente/crear', [ClienteController::class, 'create'])->name('home');
    route::post('/cliente/guardar', [ClienteController::class, 'store'])->name('home');
    route::get('/cliente/{id}/editar', [ClienteController::class, 'edit'])->name('home');
    route::Put('/cliente/{id}/actualizar', [ClienteController::class, 'update'])->name('home');
    route::delete('/cliente/{id}/eliminar', [ClienteController::class, 'destroy'])->name('home');
    //rutas para tipos de empleado
    route::get('/tipo', [TipoEmpleadoController::class, 'index'])->name('home');
    route::get('/tipo/crear', [TipoEmpleadoController::class, 'create'])->name('home');
    route::post('/tipo/guardar', [TipoEmpleadoController::class, 'store'])->name('home');
    route::get('/tipo/{id}/editar', [TipoEmpleadoController::class, 'edit'])->name('home');
    route::Put('/tipo/{id}/actualizar', [TipoEmpleadoController::class, 'update'])->name('home');
    route::delete('/tipo/{id}/eliminar', [TipoEmpleadoController::class, 'destroy'])->name('home');
    //rutas para almacen
    route::get('/almacen', [AlmacenController::class, 'index'])->name('home');
    route::get('/almacen/crear', [AlmacenController::class, 'create'])->name('home');
    route::post('/almacen/guardar', [AlmacenController::class, 'store'])->name('home');
    route::get('/almacen/{id}/editar', [AlmacenController::class, 'edit'])->name('home');
    route::Put('/almacen/{id}/actualizar', [AlmacenController::class, 'update'])->name('home');
    route::delete('/almacen/{id}/eliminar', [AlmacenController::class, 'destroy'])->name('home');
    Route::get('/almacen/pdf', [AlmacenController::class, 'downloadPDF'])->name('almacen.pdf');
    //rutas para producto
    route::get('/producto', [ProductoController::class, 'index'])->name('home');
    route::get('/producto/crear', [ProductoController::class, 'create'])->name('home');
    route::post('/producto/guardar', [ProductoController::class, 'store'])->name('home');
    route::get('/producto/{id}/editar', [ProductoController::class, 'edit'])->name('home');
    route::Put('/producto/{id}/actualizar', [ProductoController::class, 'update'])->name('home');
    route::delete('/producto/{id}/eliminar', [ProductoController::class, 'destroy'])->name('home');
    Route::get('/producto/pdf', [ProductoController::class, 'downloadPDF'])->name('producto.pdf');
    //rutas categrias
    route::get('/categoria', [CategoriaController::class, 'index'])->name('home');
    route::get('/categoria/crear', [CategoriaController::class, 'create'])->name('home');
    route::post('/categoria/guardar', [CategoriaController::class, 'store'])->name('home');
    route::get('/categoria/{id}/editar', [CategoriaController::class, 'edit'])->name('home');
    route::Put('/categoria/{id}/actualizar', [CategoriaController::class, 'update'])->name('home');
    route::delete('/categoria/{id}/eliminar', [CategoriaController::class, 'destroy'])->name('home');
    //rutas para detalle almacen
    route::get('/detalleAl', [DetalleAlmacenController::class, 'index'])->name('home');
    route::get('/detalleAl/crear', [DetalleAlmacenController::class, 'create'])->name('home');
    route::post('/detalleAl/guardar', [DetalleAlmacenController::class, 'store'])->name('home');
    route::get('/detalleAl/{id1}/{id2}/editar', [DetalleAlmacenController::class, 'edit'])->name('home');
    route::Put('/detalleAl/{id1}/{id2}/actualizar', [DetalleAlmacenController::class, 'update'])->name('home');
    route::delete('/detalleAl/{id1}/{id2}/eliminar', [DetalleAlmacenController::class, 'destroy'])->name('home');
    Route::get('/detalleAl/pdf', [DetalleAlmacenController::class, 'downloadPDF'])->name('detalleAlmacen.pdf');

    //rutas para detalle venta
    route::get('/detalleVe', [DetalleVentaController::class, 'index'])->name('home');
    route::get('/detalleVe/crear', [DetalleVentaController::class, 'create'])->name('home');
    route::post('/detalleVe/guardar', [DetalleVentaController::class, 'store'])->name('home');
    route::get('/detalleVe/{id1}/{id2}/{id3}/editar', [DetalleVentaController::class, 'edit'])->name('home');
    route::Put('/detalleVe/{id1}/{id2}/{id3}/actualizar', [DetalleVentaController::class, 'update'])->name('home');
    route::delete('/detalleVe/{id1}/{id2}/{id3}/eliminar', [DetalleVentaController::class, 'destroy'])->name('home');
    Route::get('/detalleVe/pdf', [DetalleVentaController::class, 'downloadPDF'])->name('detalleVenta.pdf');
    //rutas para categoria de productos
    route::get('/categoria', [CategoriaController::class, 'index'])->name('home');
    route::get('/categoria/crear', [CategoriaController::class, 'create'])->name('home');
    route::post('/categoria/guardar', [CategoriaController::class, 'store'])->name('home');
    route::get('/categoria/{id}/editar', [CategoriaController::class, 'edit'])->name('home');
    route::Put('/categoria/{id}/actualizar', [CategoriaController::class, 'update'])->name('home');
    route::delete('/categoria/{id}/eliminar', [CategoriaController::class, 'destroy'])->name('home');
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
