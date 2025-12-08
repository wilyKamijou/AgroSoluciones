<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use App\Models\venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\alert;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::all();
        return view('cliente.index')->with('clientes', $clientes);
    }

    public function create()
    {
        return view('cliente.create');
    }

    public function edit($id)
    {
        $cliente = cliente::find($id);
        return view('cliente.edit')->with('cliente', $cliente);
    }

    public function store(Request $request)
    {
        $ex = DB::table('clientes')
            ->where('nombreCl', $request->nombreCl)
            ->where('apellidosCl', $request->apellidosCl)
            ->exists();

        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe el cliente.');
        }
        $cliente = Cliente::create($request->all());
        $cliente->save();
        return redirect()->back()->with('success', 'Cliente se guardo correctamente.');
    }

    public function update(Request $request, $id)
    {
        $ex = DB::table('clientes')
            ->where('nombreCl', $request->nombreCl)
            ->where('apellidosCl', $request->apellidosCl)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe el cliente.');
        }
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return redirect('/cliente')->with('success', 'Cliente se guardo correctamente.');
    }

    public function destroy($id)
    {
        $existe = cliente::findOrFail($id);
        if ($existe->ventas()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene ventas asociadas.');
        }

        $existe->delete();
        return redirect()->back()->with('success', 'Cliente eliminado correctamente.');
    }
}
