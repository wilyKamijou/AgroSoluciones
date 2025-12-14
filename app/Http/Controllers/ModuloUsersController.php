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

            'rutas'          => 'required|array|min:1',
            'rutas.*'        => 'integer|exists:rutas,id_ruta',

            'name'           => 'required|string|max:255|unique:users,name',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8',
        ]);

        DB::beginTransaction();

        try {
            $rutasSeleccionadas = collect($request->rutas ?? [])
                ->map(fn ($r) => (int)$r)
                ->unique()
                ->sort()
                ->values();

            // =========================================
            // BUSCAR TIPO DE EMPLEADO EXISTENTE
            // =========================================
            $tipo = null;

            $tiposMismoNombre = TipoEmpleado::where('nombreE', $validated['nombreE'])->get();

            foreach ($tiposMismoNombre as $t) {
                $rutasTipo = DB::table('dt_rutas')
                    ->where('id_tipoE', $t->id_tipoE)
                    ->pluck('id_ruta')
                    ->map(fn ($r) => (int)$r)
                    ->sort()
                    ->values();

                if ($rutasTipo->toArray() === $rutasSeleccionadas->toArray()) {
                    $tipo = $t;
                    break;
                }
            }

            // =========================================
            // SI NO EXISTE → CREAR TIPO Y ASIGNAR RUTAS
            // =========================================
            if (!$tipo) {
                $tipo = TipoEmpleado::create([
                    'nombreE'        => $validated['nombreE'],
                    'descripcionTip' => $validated['descripcionTip'],
                ]);

                foreach ($rutasSeleccionadas as $idRuta) {
                    DB::table('dt_rutas')->insert([
                        'id_tipoE' => $tipo->id_tipoE,
                        'id_ruta'  => $idRuta,
                    ]);
                }
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
