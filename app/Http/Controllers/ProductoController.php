<?php

namespace App\Http\Controllers;

use App\Models\producto;
use App\Models\categoria;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF; // Agregar esta línea
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class ProductoController extends Controller
{
    // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF()
    {
        $productos = Producto::all();
        $categorias = categoria::all();

        $pdf = PDF::loadView('producto.pdf', compact('productos', 'categorias'));

        return $pdf->download('reporte-productos-' . date('Y-m-d') . '.pdf');
    }

    public function index()
    {
        $productos = Producto::all();
        $categorias = categoria::all();
        return view('producto.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = categoria::all();
        return view('producto.create')->with('categorias', $categorias);
    }

    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return redirect()->back()->with('success', 'Producto guardado correctamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = categoria::all();
        return view('producto.edit', compact('producto', 'categorias'));
    }


    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return redirect('/producto')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $ex = DB::table('detalle_almacens')
            ->where('id_producto', $id)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociado a los detalles de los almacenes.');
        }
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }
}
