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

    /**
     * Store a newly created resource in storage.
     */
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
            // Verificar si TODO ya existe exactamente igual
            $categoriaExistente = categoria::where('nombreCat', $validated['nombreCat'])
                ->where('descripcionCat', $validated['descripcionCat'])
                ->first();

            $almacenExistente = almacen::where('nombreAl', $validated['nombreAl'])
                ->where('descripcionAl', $validated['descripcionAl'])
                ->where('direccionAl', $validated['direccionAl'])
                ->first();

            $productoExistente = producto::where('nombrePr', $validated['nombrePr'])
                ->where('nombreTecnico', $validated['nombreTecnico'])
                ->where('descripcionPr', $validated['descripcionPr'])
                ->where('compocicionQuimica', $validated['compocicionQuimica'])
                ->where('consentracionQuimica', $validated['consentracionQuimica'])
                ->where('fechaFabricacion', $validated['fechaFabricacion'])
                ->where('fechaVencimiento', $validated['fechaVencimiento'])
                ->where('unidadMedida', $validated['unidadMedida'])
                ->where('precioPr', $validated['precioPr'])
                ->where('imagen_url', $validated['imagen_url'])
                ->first();

            // Si TODO existe exactamente igual
            if ($categoriaExistente && $almacenExistente && $productoExistente) {
                // Verificar si ya existe en el detalle de almacén
                $detalleExistente = detalleAlmacen::where('id_producto', $productoExistente->id_producto)
                    ->where('id_almacen', $almacenExistente->id_almacen)
                    ->first();

                if ($detalleExistente) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('warning', '⚠️ Este producto ya existe completamente en el sistema. Si deseas actualizar el stock, usa la opción específica de actualización de inventario.')
                        ->with('detalleExistente', $detalleExistente);
                }
            }

            // Crear o actualizar categoría
            $categoria = categoria::firstOrCreate(
                ['nombreCat' => $validated['nombreCat']],
                ['descripcionCat' => $validated['descripcionCat']]
            );

            // Crear o actualizar almacén
            $almacen = almacen::firstOrCreate(
                ['nombreAl' => $validated['nombreAl']],
                [
                    'descripcionAl' => $validated['descripcionAl'],
                    'direccionAl' => $validated['direccionAl']
                ]
            );

            // Buscar producto existente con criterios similares
            $producto = producto::where('nombrePr', $validated['nombrePr'])
                ->where('nombreTecnico', $validated['nombreTecnico'])
                ->where('descripcionPr', $validated['descripcionPr'])
                ->where('compocicionQuimica', $validated['compocicionQuimica'])
                ->first();

            if ($producto) {
                // Producto existe, actualizar algunos campos si es necesario
                $producto->update([
                    'consentracionQuimica' => $validated['consentracionQuimica'],
                    'fechaFabricacion' => $validated['fechaFabricacion'],
                    'fechaVencimiento' => $validated['fechaVencimiento'],
                    'unidadMedida' => $validated['unidadMedida'],
                    'precioPr' => $validated['precioPr'],
                    'imagen_url' => $validated['imagen_url'],
                    'id_categoria' => $categoria->id_categoria,
                ]);
            } else {
                // Crear nuevo producto
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
            }

            // Manejar detalle de almacén (stock)
            $detalle = detalleAlmacen::where('id_producto', $producto->id_producto)
                ->where('id_almacen', $almacen->id_almacen)
                ->first();

            if ($detalle) {
                // Sumar stock existente
                $detalle->update([
                    'stock' => $detalle->stock + $validated['stock'],
                    'updated_at' => now(),
                ]);
                $mensaje = 'Stock actualizado exitosamente. Stock total: ' . $detalle->stock;
            } else {
                // Crear nuevo detalle
                detalleAlmacen::create([
                    'id_producto' => $producto->id_producto,
                    'id_almacen' => $almacen->id_almacen,
                    'stock' => $validated['stock'],
                ]);
                $mensaje = 'Producto, categoría, almacén y stock guardados correctamente.';
            }

            DB::commit();

            return redirect()->back()->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

 // Método para buscar categorías
    public function buscarCategorias(Request $request)
    {
        $term = $request->get('q'); // Cambiado de 'term' a 'q'
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $categorias = categoria::where('nombreCat', 'LIKE', "%{$term}%")
            ->orWhere('descripcionCat', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id_categoria', 'nombreCat', 'descripcionCat']);
            
        return response()->json($categorias);
    }

   // Método para buscar almacenes
    public function buscarAlmacenes(Request $request)
    {
        $term = $request->get('q'); // Cambiado de 'term' a 'q'
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $almacenes = almacen::where('nombreAl', 'LIKE', "%{$term}%")
            ->orWhere('descripcionAl', 'LIKE', "%{$term}%")
            ->orWhere('direccionAl', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id_almacen', 'nombreAl', 'descripcionAl', 'direccionAl']);
            
        return response()->json($almacenes);
    }


   public function buscarProductos(Request $request)
    {
        $term = $request->get('q'); // Cambiado de 'term' a 'q'
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $productos = producto::where('nombrePr', 'LIKE', "%{$term}%")
            ->orWhere('nombreTecnico', 'LIKE', "%{$term}%")
            ->orWhere('descripcionPr', 'LIKE', "%{$term}%")
            ->with('categoria')
            ->limit(10)
            ->get(['id_producto', 'nombrePr', 'nombreTecnico', 'descripcionPr', 
                   'compocicionQuimica', 'consentracionQuimica', 'fechaFabricacion', 
                   'fechaVencimiento', 'unidadMedida', 'precioPr', 'imagen_url', 'id_categoria']);
    
        // Formatear respuesta similar al de ventas
        $formatted = $productos->map(function($producto) {
            return [
                'id_producto' => $producto->id_producto,
                'nombrePr' => $producto->nombrePr,
                'nombreTecnico' => $producto->nombreTecnico,
                'descripcionPr' => $producto->descripcionPr,
                'compocicionQuimica' => $producto->compocicionQuimica,
                'consentracionQuimica' => $producto->consentracionQuimica,
                'fechaFabricacion' => $producto->fechaFabricacion,
                'fechaVencimiento' => $producto->fechaVencimiento,
                'unidadMedida' => $producto->unidadMedida,
                'precioPr' => $producto->precioPr,
                'imagen_url' => $producto->imagen_url,
                'categoria' => $producto->categoria ? [
                    'nombreCat' => $producto->categoria->nombreCat,
                    'descripcionCat' => $producto->categoria->descripcionCat
                ] : null
            ];
        });
        
        return response()->json($formatted);
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
