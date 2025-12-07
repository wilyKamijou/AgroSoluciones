<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use App\Models\categoria;
use App\Models\detalleAlmacen;
use App\Models\producto;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de todos los campos
        $validated = $request->validate([
            // Producto
            'nombrePr' => 'required|string|max:255',
            'nombreTecnico' => 'required|string|max:255',
            'descripcionPr' => 'required|string|max:255',
            'compocicionQuimica' => 'required|string|max:255',
            'consentracionQuimica' => 'required|string|max:255',
            'fechaFabricacion' => 'required|date',
            'fechaVencimiento' => 'required|date|after_or_equal:fechaFabricacion',
            'unidadMedida' => 'required|string|max:50',
            'precioPr' => 'required|string|max:255', // tipo texto
            'stock' => 'required|string|max:255',     // tipo texto
            'imagen_url' => 'required|string|max:255',

            // Categoría
            'nombreCat' => 'required|string|max:255',
            'descripcionCat' => 'required|string|max:255',

            // Almacén
            'nombreAl' => 'required|string|max:255',
            'descripcionAl' => 'required|string|max:255',
            'direccionAl' => 'required|string|max:255',
        ]);

        try {
            // ===============================
            // 1. Categoría: usar existente o crear
            // ===============================
            $categoria = categoria::firstOrCreate(
                ['nombreCat' => $validated['nombreCat']],
                ['descripcionCat' => $validated['descripcionCat']]
            );

            // ===============================
            // 2. Almacén: usar existente o crear
            // ===============================
            $almacen = almacen::firstOrCreate(
                ['nombreAl' => $validated['nombreAl']],
                [
                    'descripcionAl' => $validated['descripcionAl'],
                    'direccionAl' => $validated['direccionAl']
                ]
            );

            // ===============================
            // 3. Producto
            // ===============================
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

            // ===============================
            // 4. Detalle Producto – Almacén
            // ===============================
            detalleAlmacen::create([
                'id_producto' => $producto->id_producto,
                'id_almacen' => $almacen->id_almacen,
                'stock' => $validated['stock'], // tipo texto
            ]);

            return redirect()->back()->with('success', 'Producto, categoría, almacén y stock guardados correctamente.');
        } catch (\Exception $e) {
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
