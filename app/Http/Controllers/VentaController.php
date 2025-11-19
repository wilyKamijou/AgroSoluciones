<?php

namespace App\Http\Controllers;

use App\Models\venta;
use App\Models\cliente;
use App\Models\empleado;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::all();
        $clientes = cliente::all();
        $empleados = empleado::all();
        return view('venta.index',compact('ventas','clientes','empleados'));
    }

    public function create()
    {   
        $clientes = cliente::all();
        $empleados = empleado::all();
        return view('venta.create',compact('clientes','empleados'));
    }

    public function edit($id)
    {
        $venta = Venta::findOrFail($id);
        $clientes = cliente::all();
        $empleados = empleado::all();
        return view('venta.edit',compact('venta','clientes','empleados'));
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
        $venta = Venta::findOrFail($id);
        $venta->delete();
        return redirect('/venta');
    }
}
