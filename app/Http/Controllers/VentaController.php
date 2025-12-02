<?php

namespace App\Http\Controllers;

use App\Models\venta;
use App\Models\cliente;
use App\Models\detalleVenta;
use App\Models\empleado;
use App\Models\Producto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Agregar esta línea
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF()
    {
        $ventas = Venta::all();
        $clientes = Cliente::all();
        $empleados = Empleado::all();

        $pdf = Pdf::loadView('venta.pdf', compact('ventas', 'clientes', 'empleados'));

        return $pdf->download('reporte-ventas-' . date('Y-m-d') . '.pdf');
    }
    public function index()
    {
        $ventas = Venta::all();
        $clientes = cliente::all();
        $empleados = empleado::all();
        $productos = Producto::all();
        return view('venta.index', compact('ventas', 'clientes', 'empleados', 'productos'));
    }

    public function create()
    {

        $clientes = cliente::all();
        $empleados = empleado::all();
        return view('venta.create', compact('clientes', 'empleados'));
    }

    public function edit($id)
    {
        $venta = Venta::findOrFail($id);
        $clientes = cliente::all();
        $empleados = empleado::all();
        return view('venta.edit', compact('venta', 'clientes', 'empleados'));
    }

    public function store(Request $request)
    {
        $venta = Venta::create($request->all());
        return redirect('/venta');
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);
        $venta->update($request->all());
        return redirect('/venta');
    }

    public function destroy($id)
    {
        $ex = DB::table('detalle_ventas')
            ->where('id_venta', $id)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociado a los detalles de las ventas.');
        }
        $venta = Venta::findOrFail($id);
        $venta->delete();
        return redirect()->back()->with('success', 'Venta eliminado correctamente.');
    }
}
