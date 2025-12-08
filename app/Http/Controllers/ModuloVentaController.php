<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use App\Models\cliente;
use App\Models\detalleAlmacen;
use App\Models\detalleVenta;
use App\Models\Empleado;
use App\Models\producto;
use App\Models\venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = producto::all();
        $empleados = Empleado::all();
        $almacenes = almacen::all();
        $detalleAs = detalleAlmacen::all();
        return view('moduloVentas.index', compact('productos', 'empleados', 'almacenes', 'detalleAs'));
    }
    public function buscar(Request $request)
    {
        $query = $request->get('q'); // lo que el usuario escribe

        $clientes = Cliente::where('nombreCl', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id_cliente', 'nombreCl', 'apellidosCl', 'telefonoCl']);

        return response()->json($clientes);
    }

    public function buscarA(Request $request)
    {
        $query = $request->get('q'); // lo que el usuario escribe en apellidos
        $clientes = Cliente::where('apellidosCl', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id_cliente', 'nombreCl', 'apellidosCl', 'telefonoCl']);

        return response()->json($clientes);
    }
    // VentaController.php
    public function verificar(Request $request)
    {
        $nombre = $request->get('nombreCl');
        $apellido = $request->get('apellidosCl');

        $cliente = Cliente::where('nombreCl', $nombre)
            ->where('apellidosCl', $apellido)
            ->first();

        if ($cliente) {
            return response()->json([
                'id_cliente' => $cliente->id_cliente,
                'nombreCl' => $cliente->nombreCl,
                'apellidosCl' => $cliente->apellidosCl,
                'telefonoCl' => $cliente->telefonoCl
            ]);
        }

        return response()->json(null);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // --- Detalle de Venta ---
        list($id_producto, $id_almacen) = explode('|', $request->idDal);

        $detalleAl = DetalleAlmacen::where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->first();

        if ($detalleAl->stock < $request->cantidadDv) {
            return redirect()->back()->with('error', 'No hay suficiente stock del producto');
        }

        // --- Cliente ---
        $cliente = Cliente::where('nombreCl', $request->nombreCl)
            ->where('apellidosCl', $request->apellidosCl)
            ->first();

        if (!$cliente) {
            $cliente = cliente::create([
                'nombreCl' => $request->nombreCl,
                'apellidosCl' => $request->apellidosCl,
                'telefonoCl' => $request->telefonoCl,
            ]);
        } else {
            $cliente->update([
                'telefonoCl' => $request->telefonoCl
            ]);
        }

        // --- Venta ---
        $venta = venta::create([
            'id_cliente' => $cliente->id_cliente,
            'id_empleado' => $request->id_empleado,
            'fechaVe' => now()->toDateString(),
            'montoTotalVe' => 0, // temporal, se actualizará luego
        ]);




        // Actualizar stock
        $detalleAl = DB::table('detalle_almacens')
            ->where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->select(
                'stock'
            )->first();
        $ac = $detalleAl->stock - $request->cantidadDv;

        DB::table('detalle_almacens')
            ->where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->update([
                'stock' => $ac
            ]);

        $producto = producto::find($id_producto); // asumir relación producto
        $total = $producto->precioPr * $request->cantidadDv;

        DB::table('detalle_ventas')
            ->insert([
                'id_venta' => $venta->id_venta,
                'id_producto' => $id_producto,
                'id_almacen' => $id_almacen,
                'cantidadDv' => $request->cantidadDv,
                'precioDv' => $producto->precioPr,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        // Actualizar monto total de venta
        $venta->montoTotalVe = $total;
        $venta->save();


        return redirect()->back()->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
