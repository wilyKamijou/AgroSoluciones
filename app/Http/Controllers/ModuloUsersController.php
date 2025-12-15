<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\rutas;
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
        $rutas = rutas::all();
        return view('moduloUsers.index', compact('rutas'));
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
    $validated = $request->validate([
        'nombreEm'       => 'required|string|max:255',
        'apellidosEm'    => 'required|string|max:255',
        'sueldoEm'       => 'required|numeric',
        'telefonoEm'     => 'required|string|max:20',
        'direccion'      => 'required|string|max:255',

        'nombreE'        => 'required|string|max:255',
        'descripcionTip' => 'required|string|max:255',
        'rutas_iniciales' => 'nullable|string|max:100', // Cambiado a rutas_iniciales

        'name'           => 'required|string|max:255|unique:users,name',
        'email'          => 'required|string|email|max:255|unique:users,email',
        'password'       => 'required|string|min:8',
    ]);

    DB::beginTransaction();

    try {
        // =========================================
        // OBTENER LAS RUTAS (INICIALES)
        // =========================================
        $rutasIniciales = $request->rutas_iniciales ?? '';
        $rutasIniciales = trim($rutasIniciales);
        
        // DEBUG: Ver qué rutas llegan
        \Log::info('Rutas iniciales recibidas:', [
            'rutas_iniciales' => $rutasIniciales,
            'rutas_array' => $request->rutas ?? []
        ]);

        // =========================================
        // BUSCAR TIPO DE EMPLEADO EXISTENTE POR NOMBRE Y RUTAS
        // =========================================
        $tipo = TipoEmpleado::where('nombreE', $validated['nombreE'])
            ->where('rutas_acceso', $rutasIniciales)
            ->first();

        // =========================================
        // SI NO EXISTE → CREAR TIPO CON RUTAS DE ACCESO
        // =========================================
        if (!$tipo) {
            $tipo = TipoEmpleado::create([
                'nombreE'        => $validated['nombreE'],
                'descripcionTip' => $validated['descripcionTip'],
                'rutas_acceso'   => $rutasIniciales // Guardar las iniciales
            ]);
            
            \Log::info('Nuevo tipo de empleado creado:', [
                'id' => $tipo->id_tipoE,
                'nombre' => $tipo->nombreE,
                'rutas_acceso' => $tipo->rutas_acceso
            ]);
        } else {
            \Log::info('Tipo de empleado existente encontrado:', [
                'id' => $tipo->id_tipoE,
                'nombre' => $tipo->nombreE,
                'rutas_acceso' => $tipo->rutas_acceso
            ]);
        }

        // =========================================
        // VALIDAR EMPLEADO DUPLICADO
        // =========================================
        $existeEmpleado = Empleado::where('nombreEm', $validated['nombreEm'])
            ->where('apellidosEm', $validated['apellidosEm'])
            ->first();

        if ($existeEmpleado) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ya existe un empleado con el mismo nombre y apellido.');
        }

        // =========================================
        // CREAR USUARIO
        // =========================================
        $usuario = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // =========================================
        // CREAR EMPLEADO
        // =========================================
        Empleado::create([
            'nombreEm'    => $validated['nombreEm'],
            'apellidosEm' => $validated['apellidosEm'],
            'sueldoEm'    => $validated['sueldoEm'],
            'telefonoEm'  => $validated['telefonoEm'],
            'direccion'   => $validated['direccion'],
            'id_tipoE'    => $tipo->id_tipoE,
            'user_id'     => $usuario->id,
        ]);

        DB::commit();
        
        return redirect()->back()->with('success', 'Registro completado correctamente. Rutas asignadas: ' . ($rutasIniciales ?: 'Ninguna'));

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error en store de ModuloUsersController: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Ocurrió un error: ' . $e->getMessage())
            ->withInput();
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
