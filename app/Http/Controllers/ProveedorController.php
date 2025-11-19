<?php

namespace App\Http\Controllers;

use App\Models\proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedors = Proveedor::all();
        return view('proveedor.index')->with('proveedors',$proveedors);
    }

    public function create()
    {
        return view('proveedor.create');
    }

    public function store(Request $request)
    {
        $proveedor = Proveedor::create($request->all());
        return redirect('/proveedor');
    }

    public function edit($id)
    {
        $proveedor=proveedor::find($id);
        return view('proveedor.edit')->with('proveedor',$proveedor);
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());
        return redirect('/proveedor');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();
        return redirect('/proveedor');
    }
}
