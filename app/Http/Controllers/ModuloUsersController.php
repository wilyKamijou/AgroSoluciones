<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\rutas;
use App\Models\tipoEmpleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    $validated = $request->validate([
        'nombreEm'       => 'required|string|max:255',
        'apellidosEm'    => 'required|string|max:255',
        'sueldoEm'       => 'required|numeric',
        'telefonoEm'     => 'required|string|max:20',
        'direccion'      => 'required|string|max:255',

        'nombreE'        => 'required|string|max:255',
        'descripcionTip' => 'required|string|max:255',
        'rutas_iniciales' => 'nullable|string|max:100',

        'name'           => 'required|string|max:255',
        'email'          => 'required|string|email|max:255',
        'password'       => 'required|string|min:8',
    ]);

    DB::beginTransaction();

    try {
        // Verificar si TODO ya existe exactamente igual
        $empleadoExistente = Empleado::where('nombreEm', $validated['nombreEm'])
            ->where('apellidosEm', $validated['apellidosEm'])
            ->first();

        // CORRECCIÓN: Buscar tipo de empleado solo por nombre y descripción
        $tipoExistente = TipoEmpleado::where('nombreE', $validated['nombreE'])
            ->where('descripcionTip', $validated['descripcionTip'])
            ->first(); // Quitamos la condición de rutas_acceso

        $usuarioExistente = User::where('name', $validated['name'])
            ->orWhere('email', $validated['email'])
            ->first();

        // Si TODO existe exactamente igual (incluyendo ahora las rutas)
        if ($empleadoExistente && $tipoExistente && $usuarioExistente) {
            // Verificar si las rutas también son iguales
            $rutasSolicitud = $validated['rutas_iniciales'] ?? '';
            if ($tipoExistente->rutas_acceso == $rutasSolicitud) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('warning', '⚠️ Este usuario, empleado y tipo ya existen completamente en el sistema (incluyendo las mismas rutas). Si deseas actualizar algo, usa la opción específica de edición.');
            }
        }

        // ============================================
        // 1. MANEJO DEL TIPO DE EMPLEADO
        // ============================================
        if ($tipoExistente) {
            // Tipo existe (mismo nombre y descripción), actualizar rutas
            $rutasActuales = $tipoExistente->rutas_acceso ?? '';
            $rutasNuevas = $validated['rutas_iniciales'] ?? '';
            
            // Solo actualizar si las rutas son diferentes
            if ($rutasActuales != $rutasNuevas) {
                $tipoExistente->update([
                    'rutas_acceso' => $rutasNuevas
                ]);
                Log::info('Tipo de empleado actualizado (rutas modificadas):', [
                    'id' => $tipoExistente->id_tipoE,
                    'nombre' => $tipoExistente->nombreE,
                    'rutas_anteriores' => $rutasActuales,
                    'rutas_nuevas' => $rutasNuevas
                ]);
            }
            $tipo = $tipoExistente;
        } else {
            // Crear nuevo tipo (nombre y/o descripción diferentes)
            $tipo = TipoEmpleado::create([
                'nombreE'        => $validated['nombreE'],
                'descripcionTip' => $validated['descripcionTip'],
                'rutas_acceso'   => $validated['rutas_iniciales'] ?? ''
            ]);
            Log::info('Nuevo tipo de empleado creado:', [
                'id' => $tipo->id_tipoE,
                'nombre' => $tipo->nombreE,
                'descripcion' => $tipo->descripcionTip,
                'rutas' => $tipo->rutas_acceso
            ]);
        }

        // ============================================
        // 2. MANEJO DEL EMPLEADO
        // ============================================
        if ($empleadoExistente) {
            // Empleado existe, actualizar datos
            $empleadoExistente->update([
                'sueldoEm'    => $validated['sueldoEm'],
                'telefonoEm'  => $validated['telefonoEm'],
                'direccion'   => $validated['direccion'],
                'id_tipoE'    => $tipo->id_tipoE,
            ]);
            $empleado = $empleadoExistente;
            $mensajeEmpleado = 'Empleado actualizado';
        } else {
            // Crear nuevo empleado
            $empleado = Empleado::create([
                'nombreEm'    => $validated['nombreEm'],
                'apellidosEm' => $validated['apellidosEm'],
                'sueldoEm'    => $validated['sueldoEm'],
                'telefonoEm'  => $validated['telefonoEm'],
                'direccion'   => $validated['direccion'],
                'id_tipoE'    => $tipo->id_tipoE,
                'user_id'     => null, // Se asignará después si hay usuario
            ]);
            $mensajeEmpleado = 'Nuevo empleado creado';
        }

        // ============================================
        // 3. MANEJO DEL USUARIO
        // ============================================
        if ($usuarioExistente) {
            // Usuario existe, actualizar datos
            $usuarioExistente->update([
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Actualizar el empleado con el ID del usuario
            $empleado->update(['user_id' => $usuarioExistente->id]);
            
            $mensajeUsuario = 'Usuario actualizado';
        } else {
            // Crear nuevo usuario
            $usuario = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Actualizar el empleado con el ID del usuario
            $empleado->update(['user_id' => $usuario->id]);
            
            $mensajeUsuario = 'Nuevo usuario creado';
        }

        DB::commit();
        
        // Construir mensaje de éxito
        $mensajes = [];
        if (isset($mensajeEmpleado)) $mensajes[] = $mensajeEmpleado;
        if (isset($mensajeUsuario)) $mensajes[] = $mensajeUsuario;
        
        // Determinar si el tipo fue creado nuevo o actualizado
        $infoTipo = $tipoExistente ? 'Existente (rutas actualizadas)' : 'Nuevo';
        
        return redirect()->back()
            ->with('success', implode(', ', $mensajes) . ' correctamente. Rutas asignadas: ' . ($validated['rutas_iniciales'] ?: 'Ninguna'))
            ->with('info', 'Tipo de empleado: ' . $infoTipo);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error en store de ModuloUsersController: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Ocurrió un error: ' . $e->getMessage())
            ->withInput();
    }
}

    // ============================================
    // MÉTODOS DE AUTOCOMPLETADO
    // ============================================

    // Método para buscar empleados
    public function buscarEmpleados(Request $request)
    {
        $term = $request->get('q');
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $empleados = Empleado::where('nombreEm', 'LIKE', "%{$term}%")
            ->orWhere('apellidosEm', 'LIKE', "%{$term}%")
            ->with('tipoEmpleado', 'user')
            ->limit(10)
            ->get(['id_empleado', 'nombreEm', 'apellidosEm', 'sueldoEm', 
                   'telefonoEm', 'direccion', 'id_tipoE', 'user_id']);
    
        $formatted = $empleados->map(function($empleado) {
            return [
                'id_empleado' => $empleado->id_empleado,
                'nombreEm' => $empleado->nombreEm,
                'apellidosEm' => $empleado->apellidosEm,
                'sueldoEm' => $empleado->sueldoEm,
                'telefonoEm' => $empleado->telefonoEm,
                'direccion' => $empleado->direccion,
                'tipoEmpleado' => $empleado->tipoEmpleado ? [
                    'id_tipoE' => $empleado->tipoEmpleado->id_tipoE,
                    'nombreE' => $empleado->tipoEmpleado->nombreE,
                    'descripcionTip' => $empleado->tipoEmpleado->descripcionTip,
                    'rutas_acceso' => $empleado->tipoEmpleado->rutas_acceso
                ] : null,
                'user' => $empleado->user ? [
                    'id' => $empleado->user->id,
                    'name' => $empleado->user->name,
                    'email' => $empleado->user->email
                ] : null
            ];
        });
        
        return response()->json($formatted);
    }

    // Método para buscar tipos de empleado
    public function buscarTiposEmpleado(Request $request)
    {
        $term = $request->get('q');
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $tipos = TipoEmpleado::where('nombreE', 'LIKE', "%{$term}%")
            ->orWhere('descripcionTip', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id_tipoE', 'nombreE', 'descripcionTip', 'rutas_acceso']);
            
        return response()->json($tipos);
    }

    // Método para buscar usuarios
    public function buscarUsuarios(Request $request)
    {
        $term = $request->get('q');
        
        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }
        
        $usuarios = User::where('name', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->with('empleado')
            ->limit(10)
            ->get(['id', 'name', 'email']);
    
        $formatted = $usuarios->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'empleado' => $user->empleado ? [
                    'id_empleado' => $user->empleado->id_empleado,
                    'nombreEm' => $user->empleado->nombreEm,
                    'apellidosEm' => $user->empleado->apellidosEm
                ] : null
            ];
        });
        
        return response()->json($formatted);
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
