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

        $validated = $request->validate([
            'nombreCl' => 'required|string|max:255',
            'apellidosCl' => 'required|string|max:255',
            'telefonoCl' => 'nullable|string|max:255',
            'id_empleado' => 'required|integer',
            'productos.*.idDal' => 'required|string',
            'productos.*.cantidadDv' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();


            $cliente = Cliente::where('nombreCl', $validated['nombreCl'])
                ->where('apellidosCl', $validated['apellidosCl'])
                ->first();

            if (!$cliente) {
                $cliente = Cliente::create([
                    'nombreCl' => $validated['nombreCl'],
                    'apellidosCl' => $validated['apellidosCl'],
                    'telefonoCl' => $validated['telefonoCl'] ?? null,
                ]);
            } else {
                $cliente->update([
                    'telefonoCl' => $validated['telefonoCl'] ?? $cliente->telefonoCl
                ]);
            }


            $venta = venta::create([
                'id_cliente' => $cliente->id_cliente,
                'id_empleado' => $validated['id_empleado'],
                'fechaVe' => now()->toDateString(),
                'montoTotalVe' => 0,
            ]);

            $montoTotal = 0;

            // ===============================
            // Productos: iterar cada producto agregado
            // ===============================
            foreach ($validated['productos'] as $p) {
                list($id_producto, $id_almacen) = explode('|', $p['idDal']);
                $cantidad = $p['cantidadDv'];

                // Obtener stock actual
                $detalleAl = DB::table('detalle_almacens')
                    ->where('id_producto', $id_producto)
                    ->where('id_almacen', $id_almacen)
                    ->first();

                if (!$detalleAl || $detalleAl->stock < $cantidad) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "No hay suficiente stock del producto ID: $id_producto");
                }

                // Actualizar stock
                DB::table('detalle_almacens')
                    ->where('id_producto', $id_producto)
                    ->where('id_almacen', $id_almacen)
                    ->update(['stock' => $detalleAl->stock - $cantidad]);

                // Obtener precio del producto
                $producto = producto::find($id_producto);
                $totalProducto = $producto->precioPr * $cantidad;
                $montoTotal += $totalProducto;

                // Insertar detalle de venta
                DB::table('detalle_ventas')->insert([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $id_producto,
                    'id_almacen' => $id_almacen,
                    'cantidadDv' => $cantidad,
                    'precioDv' => $producto->precioPr,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Actualizar monto total de la venta
            $venta->montoTotalVe = $montoTotal;
            $venta->save();

            DB::commit();
            return redirect()->back()->with('success', 'Venta registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
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
