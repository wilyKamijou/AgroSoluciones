<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\tipoEmpleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ModuloUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('moduloUsers.index');
    }

    /**
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
        // Validaciones básicas
        $validated = $request->validate([

            'nombreEm'      => 'required|string|max:255',
            'apellidosEm'   => 'required|string|max:255',
            'sueldoEm'      => 'required|numeric',
            'telefonoEm'    => 'required|string|max:20',
            'direccion'     => 'required|string|max:255',


            'nombreE'       => 'required|string|max:255',
            'descripcionTip' => 'required|string|max:255',


            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8',
        ], [
            // Mensajes personalizados solo para name y email
            'name.unique'  => 'El nombre de usuario ya está en uso.',
            'email.unique' => 'El correo electrónico ya está registrado.',
        ]);

        DB::beginTransaction();

        try {

            $tipo = TipoEmpleado::firstOrCreate(
                ['nombreE' => $validated['nombreE']],
                ['descripcionTip' => $validated['descripcionTip']]
            );

            $existeEmpleado = Empleado::where('nombreEm', $validated['nombreEm'])
                ->where('apellidosEm', $validated['apellidosEm'])
                ->first();

            if ($existeEmpleado) {

                return redirect()->back()->with('error', 'Ya existe un empleado con el mismo nombre y apellido.');
                dd($existeEmpleado);
            }


            $usuario = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);


            Empleado::create([
                'nombreEm'    => $validated['nombreEm'],
                'apellidosEm' => $validated['apellidosEm'],
                'sueldoEm'    => $validated['sueldoEm'],
                'telefonoEm'  => $validated['telefonoEm'],
                'direccion'   => $validated['direccion'],
                'id_tipoE'    => $tipo->id_tipoE,
                'user_id'     => $usuario->id
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Registro completado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
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
