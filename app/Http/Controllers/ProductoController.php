<?php

namespace App\Http\Controllers;

use App\Models\producto;
use App\Models\categoria;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('producto.index')->with('productos',$productos);
    }

    public function create()
    {
        $categorias=categoria::all();
        return view('producto.create')->with('categorias',$categorias);
    }

    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return redirect('/producto');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = categoria::all();
        return view('producto.edit', compact('producto','categorias'));
    }

    
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return redirect('/producto');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect('/producto');
    }
}
