<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\rutas;
use App\Models\tipoEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoEmpleadoController extends Controller
{
    public function index()
    {
        $tipos = TipoEmpleado::all();
        $rutas = rutas::all();
        return view('tipoE.index', compact('tipos', 'rutas'));
    }

    public function create()
    {
        return view('tipoE.create');
    }

    public function store(Request $request)
    {

        // Crear tipo de empleado y obtener ID
        $tipoEmpleadoId = DB::table('tipo_empleados')->insertGetId([
            'nombreE' => $request->nombreE,
            'descripcionTip' => $request->descripcionTip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Preparar datos para dt_rutas
        $insertData = [];
        foreach ($request->rutas as $rutaId) {
            $insertData[] = [
                'id_tipoE' => $tipoEmpleadoId,
                'id_ruta' => $rutaId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar relaciones en dt_rutas
        DB::table('dt_rutas')->insert($insertData);

        return back()->with('success', 'Tipo de empleado y rutas asignadas correctamente.');
    }
    /*
    public function store(Request $request)
    {
        $ex = tipoEmpleado::where('nombreE', $request->descripcionTip)->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede Guardar porque ya existe ese tipo de empleado.');
        }
        $tipoEmpleado = TipoEmpleado::create($request->all());
        return redirect()->back()->with('success', 'Tipo de empleados se guardo correctamente.');
    }*/
    public function edit($id)
    {
        $tipo = tipoEmpleado::find($id);
        $rutas = rutas::all();
        $rutasAsignadas = DB::table('dt_rutas')
            ->where('id_tipoE', $id)
            ->pluck('id_ruta')
            ->toArray();

        return view('tipoE.edit', compact('tipo', 'rutas', 'rutasAsignadas'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombreE'        => 'required|string|max:255',
            'descripcionTip' => 'required|string|max:255',
            'rutas'          => 'required|array|min:1',
            'rutas.*'        => 'integer'
        ]);

        // Normalizar rutas
        $rutasNuevas = collect($validated['rutas'])
            ->map(fn ($r) => (int)$r)
            ->unique()
            ->sort()
            ->values();

        DB::beginTransaction();

        try {

            //  Tipo actual
            $tipoActual = TipoEmpleado::find($id);
            if (!$tipoActual) {
                return redirect()->back()->with('error', 'Tipo no encontrado.');
            }

            //  Buscar duplicado (nombre + rutas)
            $otrosTipos = DB::table('tipo_empleados')
                ->where('nombreE', $validated['nombreE'])
                ->where('id_tipoE', '!=', $id)
                ->get();

            foreach ($otrosTipos as $otro) {

                $rutasOtro = DB::table('dt_rutas')
                    ->where('id_tipoE', $otro->id_tipoE)
                    ->pluck('id_ruta')
                    ->map(fn ($r) => (int)$r)
                    ->sort()
                    ->values();

                if ($rutasOtro->toArray() === $rutasNuevas->toArray()) {
                    DB::rollBack();
                    return redirect()->back()->with(
                        'error',
                        'Ya existe un tipo de empleado con el mismo nombre y las mismas rutas.'
                    );
                }
            }

            //  Actualizar tipo_empleados
            $tipoActual->update([
                'nombreE'        => $validated['nombreE'],
                'descripcionTip' => $validated['descripcionTip'],
            ]);

            //  Reemplazar rutas
            DB::table('dt_rutas')
                ->where('id_tipoE', $id)
                ->delete();

            foreach ($rutasNuevas as $rutaId) {
                DB::table('dt_rutas')->insert([
                    'id_tipoE' => $id,
                    'id_ruta'  => $rutaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect('/tipo')->with(
                'success',
                'Tipo de empleado actualizado correctamente.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(
                'error',
                'Error al actualizar: ' . $e->getMessage()
            );
        }
    }

    public function destroy($id)
    {
        $ex = TipoEmpleado::findOrFail($id);
        if ($ex->empleados()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene empleadoas asociados.');
        }
        $ex->delete();
        return redirect()->back()->with('success', 'Tipo de empleados se eliminado correctamente.');
    }
}
