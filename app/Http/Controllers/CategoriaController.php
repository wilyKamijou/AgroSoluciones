<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categoria.index')->with('categorias', $categorias);
    }

    public function create()
    {
        return view('categoria.create');
    }

    public function store(Request $request)
    {
        $ex = DB::table('categorias')
            ->where('nombreCat', $request->nombreCat)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar, ya existe esta categoria.');
        }
        $categoria = Categoria::create($request->all());
        return redirect('/categoria')->with('success', 'Categoria guardada correctamente.');
    }

    public function edit($id)
    {
        $categoria = categoria::find($id);
        return view('categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $ex = DB::table('categorias')
            ->where('nombreCat', $request->nombreCat)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede actualizar, ya existe esta categoria.');
        }
        $cat = Categoria::findOrFail($id);
        $cat->update($request->all());
        return redirect('/categoria')->with('success', 'Categoria actualizada correctamente.');
    }

    public function destroy($id)
    {
        $existe = categoria::findOrFail($id);
        if ($existe->productos()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene productos asociados.');
        }

        $existe->delete();
        return redirect()->back()->with('success', 'Categoria eliminado correctamente.');
    }
}
