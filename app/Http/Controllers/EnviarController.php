<?php

namespace App\Http\Controllers;

use App\Mail\QuejaMail;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class enviarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function enviar(Request $request)
    {

        $request->validate([
            'nombre'  => 'required',
            'email'   => 'required|email',
            'asunto'  => 'required',
            'mensaje' => 'required',
        ]);
        /*
        $adminEmail = DB::table('tipo_empleado')
            ->where('nombreE', 'Owner')->first();
*/
        // Enviar correo usando la clase QuejaMail
        Mail::to('wilianxd474@gmail.com')->send(new QuejaMail($request->all()));

        // Retornar con mensaje de éxito
        return back()->with('success', 'Tu mensaje ha sido enviado con éxito.');
    }

    /**..........................................................................................................................
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
