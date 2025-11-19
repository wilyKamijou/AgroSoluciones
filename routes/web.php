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
use GuzzleHttp\Client;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();



Route::middleware('auth')->group(function () {


Route::get('/home', [App\Http\Controllers\HomeController::class, 'home']);

    //rutas de empleado
    route::get('/empleado', [EmpleadoController::class, 'index'])->name('home');
    route::get('/empleado/crear', [EmpleadoController::class, 'create'])->name('home');
    route::post('/empleado/guardar',[EmpleadoController::class, 'store'])->name('home');
    route::get('/empleado/{id}/editar',[EmpleadoController::class, 'edit'])->name('home');
    route::Put('/empleado/{id}/actualizar', [EmpleadoController::class, 'update'])->name('home');
    route::delete('/empleado/{id}/eliminar', [EmpleadoController::class, 'destroy'])->name('home');
    //rutas de venta
    route::get('/venta', [VentaController::class, 'index'])->name('home');
    route::get('/venta/crear', [VentaController::class, 'create'])->name('home');
    route::post('/venta/guardar',[VentaController::class, 'store'])->name('home');
    route::get('/venta/{id}/editar',[VentaController::class, 'edit'])->name('home');
    route::Put('/venta/{id}/actualizar', [VentaController::class, 'update'])->name('home');
    route::delete('/venta/{id}/eliminar', [VentaController::class, 'destroy'])->name('home');
    //rutas de cliente
    route::get('/cliente', [ClienteController::class, 'index'])->name('home');
    route::get('/cliente/crear', [ClienteController::class, 'create'])->name('home');
    route::post('/cliente/guardar',[ClienteController::class, 'store'])->name('home');
    route::get('/cliente/{id}/editar',[ClienteController::class, 'edit'])->name('home');
    route::Put('/cliente/{id}/actualizar', [ClienteController::class, 'update'])->name('home');
    route::delete('/cliente/{id}/eliminar', [ClienteController::class, 'destroy'])->name('home');
    //rutas para proveedores
    route::get('/proveedor', [ProveedorController::class, 'index'])->name('home');
    route::get('/proveedor/crear', [ProveedorController::class, 'create'])->name('home');
    route::post('/proveedor/guardar',[ProveedorController::class, 'store'])->name('home');
    route::get('/proveedor/{id}/editar',[ProveedorController::class, 'edit'])->name('home');
    route::Put('/proveedor/{id}/actualizar', [ProveedorController::class, 'update'])->name('home');
    route::delete('/proveedor/{id}/eliminar', [ProveedorController::class, 'destroy'])->name('home');
    //rutas para proveedores
    route::get('/categoria', [CategoriaController::class, 'index'])->name('home');
    route::get('/categoria/crear', [CategoriaController::class, 'create'])->name('home');
    route::post('/categoria/guardar',[CategoriaController::class, 'store'])->name('home');
    route::get('/categoria/{id}/editar',[CategoriaController::class, 'edit'])->name('home');
    route::Put('/categoria/{id}/actualizar', [CategoriaController::class, 'update'])->name('home');
    route::delete('/categoria/{id}/eliminar', [CategoriaController::class, 'destroy'])->name('home');
    //rutas para tipos de empleado
    route::get('/tipo', [TipoEmpleadoController::class, 'index'])->name('home');
    route::get('/tipo/crear', [TipoEmpleadoController::class, 'create'])->name('home');
    route::post('/tipo/guardar',[TipoEmpleadoController::class, 'store'])->name('home');
    route::get('/tipo/{id}/editar',[TipoEmpleadoController::class, 'edit'])->name('home');
    route::Put('/tipo/{id}/actualizar', [TipoEmpleadoController::class, 'update'])->name('home');
    route::delete('/tipo/{id}/eliminar', [TipoEmpleadoController::class, 'destroy'])->name('home');
    //rutas para almacene
    route::get('/almacen', [AlmacenController::class, 'index'])->name('home');
    route::get('/almacen/crear', [AlmacenController::class, 'create'])->name('home');
    route::post('/almacen/guardar',[AlmacenController::class, 'store'])->name('home');
    route::get('/almacen/{id}/editar',[AlmacenController::class, 'edit'])->name('home');
    route::Put('/almacen/{id}/actualizar', [AlmacenController::class, 'update'])->name('home');
    route::delete('/almacen/{id}/eliminar', [AlmacenController::class, 'destroy'])->name('home');
    //rutas para producto
    route::get('/producto', [ProductoController::class, 'index'])->name('home');
    route::get('/producto/crear', [ProductoController::class, 'create'])->name('home');
    route::post('/producto/guardar',[ProductoController::class, 'store'])->name('home');
    route::get('/producto/{id}/editar',[ProductoController::class, 'edit'])->name('home');
    route::Put('/producto/{id}/actualizar', [ProductoController::class, 'update'])->name('home');
    route::delete('/producto/{id}/eliminar', [ProductoController::class, 'destroy'])->name('home');
    //rutas para compra
    route::get('/compra', [CompraController::class, 'index'])->name('home');
    route::get('/compra/crear', [CompraController::class, 'create'])->name('home');
    route::post('/compra/guardar',[CompraController::class, 'store'])->name('home');
    route::get('/compra/{id}/editar',[CompraController::class, 'edit'])->name('home');
    route::Put('/compra/{id}/actualizar', [CompraController::class, 'update'])->name('home');
    route::delete('/compra/{id}/eliminar', [CompraController::class, 'destroy'])->name('home');
    //rutas para detalle almacen
    route::get('/detalleAl', [DetalleAlmacenController::class, 'index'])->name('home');
    route::get('/detalleAl/crear', [DetalleAlmacenController::class, 'create'])->name('home');
    route::post('/detalleAl/guardar',[DetalleAlmacenController::class, 'store'])->name('home');
    route::get('/detalleAl/{id1}/{id2}/editar',[DetalleAlmacenController::class, 'edit'])->name('home');
    route::Put('/detalleAl/{id1}/{id2}/actualizar', [DetalleAlmacenController::class, 'update'])->name('home');
    route::delete('/detalleAl/{id1}/{id2}/eliminar', [DetalleAlmacenController::class, 'destroy'])->name('home');
});
