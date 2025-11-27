<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF; // Agregar esta línea
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF(Request $request)
    {
        $almacens = Almacen::all();

        $pdf = PDF::loadView('almacen.pdf', compact('almacens'));

        // Si se solicita ver en el navegador
        if ($request->has('action') && $request->action == 'view') {
            return $pdf->stream('reporte-almacenes-' . date('Y-m-d') . '.pdf');
        }

        // Por defecto descarga
        return $pdf->download('reporte-almacenes-' . date('Y-m-d') . '.pdf');
    }
    public function index()
    {
        $almacens = Almacen::all();
        return view('almacen.index')->with('almacens', $almacens);
    }

    public function create()
    {
        return view('almacen.create');
    }

    public function store(Request $request)
    {
        $ex = DB::table('almacens')
            ->where('nombreAl', $request->nombreAl)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar ya existe este alamacen.');
        }
        $almacen = Almacen::create($request->all());
        return redirect()->back()->with('success', 'Almacen guardado correctamente.');
    }
    public function edit($id)
    {
        $almacen = almacen::find($id);
        return view('almacen.edit')->with('almacen', $almacen);
    }
    public function update(Request $request, $id)
    {
        $ex = DB::table('almacens')
            ->where('nombreAl', $request->nombreAl)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede actualizar ya existe este alamacen.');
        }
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->all());
        return redirect('/almacen')->with('success', 'Almacen actualizado correctamente.');
    }

    public function destroy($id)
    {
        $al = almacen::find($id);
        $ex = DB::table('detalle_almacens')
            ->where('id_almacen', $al->id_almacen)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociado a los detalles de los almacenes.');
        }
        $al->delete();
        return redirect()->back()->with('success', 'Almacen eliminado correctamente.');
    }
}
