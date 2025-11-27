<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\tipoEmpleado;
use Illuminate\Http\Request;

class TipoEmpleadoController extends Controller
{
    public function index()
    {
        $tipos = TipoEmpleado::all();
        return view('tipoE.index')->with('tipos', $tipos);
    }

    public function create()
    {
        return view('tipoE.create');
    }

    public function store(Request $request)
    {
        $ex = tipoEmpleado::where('descripcionTip', $request->descripcionTip)->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe ese tipo de empleado.');
        }
        $tipoEmpleado = TipoEmpleado::create($request->all());
        return redirect()->back()->with('success', 'Tipo de empleados se guardo correctamente.');
    }
    public function edit($id)
    {
        $tipo = tipoEmpleado::find($id);
        return view('tipoE.edit')->with('tipo', $tipo);
    }
    public function update(Request $request, $id)
    {
        $ex = TipoEmpleado::where('descripcionTip', $request->descripcionTip)->exists();
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
