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

        // Crear tipo de empleado y obtener ID
        $tipoEmpleadoId = DB::table('tipo_empleados')->insertGetId([
            'nombreE' => $request->nombreE,
            'descripcionTip' => $request->descripcionTip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Preparar datos para dt_rutas
        $insertData = [];
        foreach ($request->rutas as $rutaId) {
            $insertData[] = [
                'id_tipoE' => $tipoEmpleadoId,
                'id_ruta' => $rutaId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar relaciones en dt_rutas
        DB::table('dt_rutas')->insert($insertData);

        return back()->with('success', 'Tipo de empleado y rutas asignadas correctamente.');
    }
    /*
    public function store(Request $request)
    {
        $ex = tipoEmpleado::where('nombreE', $request->descripcionTip)->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe ese tipo de empleado.');
        }
        $tipoEmpleado = TipoEmpleado::create($request->all());
        return redirect()->back()->with('success', 'Tipo de empleados se guardo correctamente.');
    }*/
    public function edit($id)
    {
        $tipo = tipoEmpleado::find($id);
        return view('tipoE.edit')->with('tipo', $tipo);
    }
    public function update(Request $request, $id)
    {
        $ex = TipoEmpleado::where('nombreE', $request->descripcionTip)->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe ese tipo de empleado.');
        }
        $new = tipoEmpleado::find($id);
        $new->update($request->all());
        return redirect('/tipo')->with('success', 'Tipo de empleados se actualizado correctamente.');
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
