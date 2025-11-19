<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('cliente.index')->with('clientes',$clientes);
    }

    public function create()
    {
        return view('cliente.create');
    }

    public function edit($id)
    {
        $cliente=cliente::find($id);
        return view('cliente.edit')->with('cliente',$cliente);
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());
        $cliente->save();
        return redirect('/cliente');
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return redirect('/cliente');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect('/cliente');
    }
}
