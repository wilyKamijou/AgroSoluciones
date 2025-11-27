<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF; // Agregar esta línea

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
        return view('almacen.index')->with('almacens',$almacens);
    }

    public function create()
    {
        return view('almacen.create');
    }

    public function store(Request $request)
    {
        $almacen = Almacen::create($request->all());
        return redirect('/almacen');
    }
    public function edit($id)
    {
        $almacen=almacen::find($id);
        return view('almacen.edit')->with('almacen',$almacen);
    }
    public function update(Request $request, $id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->all());
        return redirect('/almacen');
    }

    public function destroy($id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->delete();
        return redirect('almacen');
    }
}
