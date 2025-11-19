<?php

namespace App\Http\Controllers;

use App\Models\tipoEmpleado;
use Illuminate\Http\Request;

class TipoEmpleadoController extends Controller
{
    public function index()
    {
        $tipos = TipoEmpleado::all();
        return view('tipoE.index')->with('tipos',$tipos);
    }

    public function create()
    {
        return view('tipoE.create');
    }
    
    public function store(Request $request)
    {
        $tipoEmpleado = TipoEmpleado::create($request->all());
        return redirect('/tipo');
    }
    public function edit($id)
    {
        $tipo=tipoEmpleado::find($id);
        return view('tipoE.edit')->with('tipo',$tipo);
    }
    public function update(Request $request, $id)
    {
        $tipoEmpleado = TipoEmpleado::findOrFail($id);
        $tipoEmpleado->update($request->all());
        return redirect('/tipo');
    }

    public function destroy($id)
    {
        $tipoEmpleado = TipoEmpleado::findOrFail($id);
        $tipoEmpleado->delete();
        return redirect('/tipo');
    }
}
