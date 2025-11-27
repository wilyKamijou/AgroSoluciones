<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use App\Models\cliente;
use App\Models\detalleAlmacen;
use App\Models\detalleVenta;
use App\Models\producto;
use App\Models\venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Agregar esta línea

class DetalleVentaController extends Controller
{

        // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF(Request $request)
    {
        $detalleVs = detalleVenta::all();
        $ventas = venta::all();
        $productos = producto::all();
        $almacenes = almacen::all();
        $clientes = cliente::all();
        
        $pdf = Pdf::loadView('detalleVenta.pdf', compact('detalleVs', 'ventas', 'productos', 'almacenes', 'clientes'));
        
        // Si se solicita ver en el navegador
        if ($request->has('action') && $request->action == 'view') {
            return $pdf->stream('reporte-detalles-venta-' . date('Y-m-d') . '.pdf');
        }
        
        // Por defecto descarga
        return $pdf->download('reporte-detalles-venta-' . date('Y-m-d') . '.pdf');
    }

    public function index()
    {
        $detalleVs = detalleVenta::all();
        $ventas = venta::all();
        $productos = producto::all();
        $almacenes = almacen::all();
        $clientes = cliente::all();
        $detalleAs = detalleAlmacen::all();
        //return response()->json($detallesVentas);
        return view('detalleVenta.index', compact('detalleVs', 'ventas', 'productos', 'almacenes', 'clientes', 'detalleAs'));
    }

    public function create()
    {
        $ventas = venta::all();
        $detalleAs = detalleAlmacen::all();
        $productos = producto::all();
        $almacenes = almacen::all();
        $clientes = cliente::all();
        return view('detalleVenta.create', compact('ventas', 'detalleAs', 'productos', 'almacenes', 'clientes'));
    }

    public function store(Request $request)
    {

        list($id_producto, $id_almacen) = explode('|', $request->idDal);
        $dt = DetalleVenta::create();
        $dt->cantidadDV = $request->cantidadDv;
        $dt->precioDv = $request->precioDv;
        $dt->id_venta = $request->id_venta;
        $dt->id_producto = $id_producto;
        $dt->id_almacen = $id_almacen;
        $dt->save();
        return redirect('/detalleVe');
    }

    public function update(Request $request, $id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->update($request->all());
        return response()->json($detalleVenta);
    }

    public function destroy($id1, $id2, $id3)
    {
        DB::table('detalle_ventas')
            ->where('id_venta', $id1)
            ->where('id_producto', $id2)
            ->where('id_almacen', $id3)
            ->delete();
        return redirect('/detalleVe')->with('success', 'Registro eliminado correctamente.');
    }
}
