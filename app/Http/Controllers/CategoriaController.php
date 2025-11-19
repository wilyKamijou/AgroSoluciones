<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categoria.index')->with('categorias',$categorias);
    }

    public function create()
    {
        return view('categoria.create');
    }

    public function store(Request $request)
    {
        $categoria = Categoria::create($request->all());
        return redirect('/categoria');
    }

    public function edit($id)
    {
        $categoria=categoria::find($id);
        return view('categoria.edit')->with('categoria',$categoria);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        return redirect('/categoria');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect('/categoria');
    }
}
