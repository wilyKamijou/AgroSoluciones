<?php

namespace App\Http\Controllers;

use App\Models\almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
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
