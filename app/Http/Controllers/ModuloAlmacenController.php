<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use App\Models\categoria;
use App\Models\detalleAlmacen;
use App\Models\producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('moduloInventario.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validated = $request->validate([

            'nombrePr' => 'required|string|max:255',
            'nombreTecnico' => 'required|string|max:255',
            'descripcionPr' => 'required|string|max:255',
            'compocicionQuimica' => 'required|string|max:255',
            'consentracionQuimica' => 'required|string|max:255',
            'fechaFabricacion' => 'required|date',
            'fechaVencimiento' => 'required|date|after_or_equal:fechaFabricacion',
            'unidadMedida' => 'required|string|max:50',
            'precioPr' => 'required|string|max:255',
            'stock' => 'required|string|max:255',
            'imagen_url' => 'required|string|max:255',


            'nombreCat' => 'required|string|max:255',
            'descripcionCat' => 'required|string|max:255',


            'nombreAl' => 'required|string|max:255',
            'descripcionAl' => 'required|string|max:255',
            'direccionAl' => 'required|string|max:255',
        ]);


        DB::beginTransaction();

        try {

            $categoria = categoria::firstOrCreate(
                ['nombreCat' => $validated['nombreCat']],
                ['descripcionCat' => $validated['descripcionCat']]
            );

            $almacen = almacen::firstOrCreate(
                ['nombreAl' => $validated['nombreAl']],
                [
                    'descripcionAl' => $validated['descripcionAl'],
                    'direccionAl' => $validated['direccionAl']
                ]
            );


            //  Buscar producto existente

            $producto = producto::where('nombrePr', $validated['nombrePr'])
                ->where('fechaFabricacion', $validated['fechaFabricacion'])
                ->where('fechaVencimiento', $validated['fechaVencimiento'])
                ->where('unidadMedida', $validated['unidadMedida'])
                ->first();

            if ($producto) {

                $detalle = DB::table('detalle_almacens')
                    ->where('id_producto', $producto->id_producto)
                    ->where('id_almacen', $almacen->id_almacen)
                    ->first();

                if ($detalle) {
                    // SUMAR STOCK
                    DB::table('detalle_almacens')
                        ->where('id_producto', $producto->id_producto)
                        ->where('id_almacen', $almacen->id_almacen)
                        ->update([
                            'stock' => $detalle->stock + $validated['stock'],
                            'updated_at' => now(),
                        ]);
                } else {

                    DB::table('detalle_almacens')->insert([
                        'id_producto' => $producto->id_producto,
                        'id_almacen' => $almacen->id_almacen,
                        'stock' => $validated['stock'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {

                $producto = producto::create([
                    'nombrePr' => $validated['nombrePr'],
                    'nombreTecnico' => $validated['nombreTecnico'],
                    'descripcionPr' => $validated['descripcionPr'],
                    'compocicionQuimica' => $validated['compocicionQuimica'],
                    'consentracionQuimica' => $validated['consentracionQuimica'],
                    'fechaFabricacion' => $validated['fechaFabricacion'],
                    'fechaVencimiento' => $validated['fechaVencimiento'],
                    'unidadMedida' => $validated['unidadMedida'],
                    'precioPr' => $validated['precioPr'],
                    'imagen_url' => $validated['imagen_url'],
                    'id_categoria' => $categoria->id_categoria,
                ]);

                DB::table('detalle_almacens')->insert([
                    'id_producto' => $producto->id_producto,
                    'id_almacen' => $almacen->id_almacen,
                    'stock' => $validated['stock'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();


            return redirect()->back()->with('success', 'Producto, categoría, almacén y stock guardados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al guardar: ' . $e->getMessage());
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
