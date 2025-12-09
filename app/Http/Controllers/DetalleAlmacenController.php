<?php

namespace App\Http\Controllers;

use App\Models\detalleAlmacen;
use App\Models\almacen;
use App\Models\producto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF; // Agregar esta línea
use Illuminate\Support\Facades\DB;

class DetalleAlmacenController extends Controller
{
    // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF(Request $request)
    {
        $detalles = DetalleAlmacen::all();
        $almacens = almacen::all();
        $productos = producto::all();

        $pdf = PDF::loadView('detalleAl.pdf', compact('detalles', 'almacens', 'productos'));

        // Si se solicita ver en el navegador
        if ($request->has('action') && $request->action == 'view') {
            return $pdf->stream('reporte-detalles-almacen-' . date('Y-m-d') . '.pdf');
        }

        // Por defecto descarga
        return $pdf->download('reporte-detalles-almacen-' . date('Y-m-d') . '.pdf');
    }
    public function index()
    {
        $detalles = DetalleAlmacen::all();
        $almacens = almacen::all();
        $productos = producto::all();

        return view('detalleAl.index', compact('detalles', 'almacens', 'productos'));
    }

    public function create()
    {
        $almacens = almacen::all();
        $productos = producto::all();
        return view('detalleAl.create', compact('almacens', 'productos'));
    }
    public function edit($id1, $id2)
    {
        $almacens = almacen::all();
        $productos = producto::all();
        $detalle = DetalleAlmacen::where('id_producto', $id1)->where('id_almacen', $id2)->first();
        return view('detalleAl.edit', compact('detalle', 'productos', 'almacens'));
    }

    public function store(Request $request)
    {
        $ex = DB::table('detalle_almacens')
            ->where('id_producto', $request->id_producto)
            ->where('id_almacen', $request->id_almacen)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar porque este detalle almacen ya existe.');
        }
        $detalle = new  detalleAlmacen();
        $detalle->stock = $request->input('stock');
        $detalle->id_producto = $request->input('id_producto');
        $detalle->id_almacen = $request->input('id_almacen');
        $detalle->save();
        return redirect()->back()->with('success', 'Detalle de almacen guardado correctamente.');
    }

    public function update(Request $request, $id1, $id2)
    {
        $ex = DB::table('detalle_almacens')
            ->where('id_producto', $request->id_producto)
            ->where('id_almacen', $request->id_almacen)
            ->exists();
        if ($ex) {
            if ($id1 == $request->id_producto and $id2 == $request->id_almacen) {
                $dt = DB::table('detalle_almacens')
                    ->where('id_producto', $id1)
                    ->where('id_almacen', $id2)
                    ->update([
                        'stock' => $request->stock
                    ]);
                return redirect('/detalleAl')->with('success', 'Detalle de almacen actulizado correctamente.');
            }
            return redirect()->back()->with('error', 'No se puede actulizar porque este detalle almacen ya existe.');
        }
        $dt = DB::table('detalle_almacens')
            ->where('id_producto', $id1)
            ->where('id_almacen', $id2)
            ->update([
                'id_producto' => $request->id_producto,
                'id_almacen' => $request->id_almacen,
                'stock' => $request->stock
            ]);
        return redirect('/detalleAl')->with('success', 'Detalle de almacen actulizado correctamente.');
    }

    public function destroy($id1, $id2)
    {
        $ex = DB::table('detalle_ventas')
            ->where('id_producto', $id1)
            ->where('id_almacen', $id2)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociados a detalles de venta.');
        }
        DB::table('detalle_almacens')
            ->where('id_producto', $id1)
            ->where('id_almacen', $id2)
            ->delete();
        return redirect()->back()->with('success', 'Detalle de almacen eliminado correctamente.');
    }
}
