<?php

namespace App\Http\Controllers;

use App\Models\detalleAlmacen;
use App\Models\almacen;
use App\Models\producto;
use Illuminate\Http\Request;

class DetalleAlmacenController extends Controller
{
    public function index()
    {
        $detalles = DetalleAlmacen::all();
        $almacens =almacen::all();
        $productos=producto::all();

        return view('detalleAl.index',compact('detalles','almacens','productos'));
    }

    public function create()
    {
        $almacens =almacen::all();
        $productos=producto::all();
        return view('detalleAl.create',compact('almacens','productos'));
    }
    public function edit($id1,$id2)
    {   
        $almacenes =almacen::all();
        $productos=producto::all();
        $detalle = DetalleAlmacen::where('id_producto',$id1)->where('id_almacen',$id2)->first();
        return view('detalleAl.edit',compact('detalle','productos','almacenes'));
    }

    public function store(Request $request)
    {
        $detalle =new  detalleAlmacen();
        $detalle->stock=$request->input('stock');
        $detalle->id_producto=$request->input('id_producto');
        $detalle->id_almacen=$request->input('id_almacen');
        $detalle->save();
        return redirect('/detalleAl');
    }

    public function update(Request $request, $id1,$id2)
    {
        
        $detalle = DetalleAlmacen::where('id_producto',$id1)->where('id_almacen',$id2)->first();
        $detalle->update($request->all());
        return redirect('/detalleAl');
     
       
    }

    public function destroy($id1,$id2)
    {
        $detalleAlmacen = DetalleAlmacen::where('id_producto',$id1)->where('id_almacen',$id2)->first();
        $detalleAlmacen->delete();
        return redirect('/detalleAl');
    }
}
