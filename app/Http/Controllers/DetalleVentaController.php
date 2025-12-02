<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use App\Models\cliente;
use App\Models\compra;
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
        $ex = DB::table('detalle_ventas')
            ->where('id_venta', $request->id_venta)
            ->where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar porque este detalle venta ya existe.');
        } else {
            $al = DB::table('detalle_almacens')
                ->where('id_producto', $id_producto)
                ->where('id_almacen', $id_almacen)
                ->first();
            if ($al->stock >= $request->cantidadDv) {
                $dt = detalleVenta::create([
                    'cantidadDv' => $request->cantidadDv,
                    'precioDv' => $request->precioDv,
                    'id_venta' => $request->id_venta,
                    'id_producto' => $id_producto,
                    'id_almacen' => $id_almacen
                ]);
                DB::table('ventas')
                    ->where('id_venta', $request->id_venta)
                    ->update([
                        'montoTotalVe' => $request->precioDv * $request->cantidadDv
                    ]);
                DB::table('detalle_almacens')
                    ->where('id_producto', $id_producto)
                    ->where('id_almacen', $id_almacen)
                    ->update([
                        'stock' => $al->stock - $request->cantidadDv
                    ]);
                return redirect()->back()->with('success', 'Detalle de venta guardado correctamente.');
            }
            return redirect()->back()->with('error', 'No se puede guardar porque no alcansa el stock.');
        }
    }
    public function edit($id1, $id2, $id3)
    {
        $ventas = venta::all();
        $detalleAs = detalleAlmacen::all();
        $almacenes = almacen::all();
        $productos = producto::all();
        $detalleVe = detalleVenta::where('id_venta', $id1)->where('id_producto', $id2)->where('id_almacen', $id3)->first();
        return view('detalleVenta.edit', compact('ventas', 'detalleAs', 'almacenes', 'productos', 'detalleVe'));
    }
    public function update(Request $request, $id1, $id2, $id3)
    {
        list($id_producto, $id_almacen) = explode('|', $request->idDal);
        $ex = DB::table('detalle_ventas')
            ->where('id_venta', $request->id_venta)
            ->where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->exists();
        if ($ex) {
            if ($id1 == $request->id_venta and $id2 == $id_producto and $id3 == $id_almacen) {
                DB::table('detalle_ventas')
                    ->where('id_venta', $id1)
                    ->where('id_producto', $id2)
                    ->where('id_almacen', $id3)
                    ->update([
                        'cantidadDv' => $request->cantidadDv,
                        'precioDv' => $request->precioDv
                    ]);
                return redirect('/detalleVe')->with('success', 'Detalle de venta actulizado correctamente.');
            }
            return redirect()->back()->with('error', 'No se puede actulizar porque este detalle venta ya existe.');
        }
        DB::table('detalle_ventas')
            ->where('id_venta', $request->id_venta)
            ->where('id_producto', $id_producto)
            ->where('id_almacen', $id_almacen)
            ->update([
                'id_venta' => $request->id_venta,
                'id_producto' => $id_producto,
                'id_almacen' => $id_almacen,
                'cantidadDv' => $request->cantidadDv,
                'precioDv' => $request->precioDv
            ]);
        return redirect('/detalleAl')->with('success', 'Detalle de venta actulizado correctamente.');
    }

    public function destroy($id1, $id2, $id3)
    {
        DB::table('detalle_ventas')
            ->where('id_venta', $id1)
            ->where('id_producto', $id2)
            ->where('id_almacen', $id3)
            ->delete();
        return redirect('/detalleVe')->with('success', 'Detalle de venta eliminado correctamente.');
    }
}
