<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\rutas;
use App\Models\tipoEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoEmpleadoController extends Controller
{
    public function index()
    {
        $tipos = TipoEmpleado::all();
        $rutas = rutas::all();
        return view('tipoE.index', compact('tipos', 'rutas'));
    }

    public function create()
    {
        return view('tipoE.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nombreE' => 'required|string|max:255',
        'descripcionTip' => 'required|string|max:500',
    ]);

    tipoEmpleado::create([
        'nombreE' => $request->nombreE,
        'descripcionTip' => $request->descripcionTip,
        'rutas_acceso' => $request->rutas_iniciales,
    ]);

    return back()->with('success', 'Tipo de empleado creado correctamente.');
}
    public function edit($id)
    {
        $tipo = tipoEmpleado::find($id);
        $rutas = rutas::all();
        $rutasAsignadas = DB::table('dt_rutas')
            ->where('id_tipoE', $id)
            ->pluck('id_ruta')
            ->toArray();

        return view('tipoE.edit', compact('tipo', 'rutas', 'rutasAsignadas'));
    }

public function update(Request $request, $id)
{
    // Validación
    $request->validate([
        'nombreE' => 'required|string|max:255',
        'descripcionTip' => 'required|string|max:500',
        'rutas_iniciales' => 'nullable|string|max:100'
    ]);


    // Buscar y actualizar
    $tipo = tipoEmpleado::findOrFail($id);
    
    // Actualizar solo los campos que necesitas
    $tipo->nombreE = $request->nombreE;
    $tipo->descripcionTip = $request->descripcionTip;
    $tipo->rutas_acceso = $request->rutas_iniciales; // ¡IMPORTANTE!
    
    $tipo->save();

    return redirect('/tipo')->with('success', 'Tipo de empleado actualizado correctamente.');
}


    public function destroy($id)
    {
        $ex = TipoEmpleado::findOrFail($id);
        if ($ex->empleados()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene empleadoas asociados.');
        }
        $ex->delete();
        return redirect()->back()->with('success', 'Tipo de empleados se eliminado correctamente.');
    }
}
