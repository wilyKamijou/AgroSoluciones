<?php
// app/Http/Controllers/EmpleadoController.php
namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Login;
use App\Models\TipoEmpleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\PDF; // Agregar esta línea
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    // ✅ NUEVO MÉTODO PARA GENERAR PDF
    public function downloadPDF()
    {
        $empleados = Empleado::all();
        $tipos = TipoEmpleado::all();
        $cuentas = User::all();

        $pdf = PDF::loadView('empleado.pdf', compact('empleados', 'tipos', 'cuentas'));

        return $pdf->download('reporte-empleados-' . date('Y-m-d') . '.pdf');
    }

    public function index()
    {
        $empleados = empleado::all();
        $tipos = TipoEmpleado::all();
        $cuentas = User::all();
        return view('empleado.index', compact('empleados', 'tipos', 'cuentas'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos = TipoEmpleado::all();
        $cuentas = User::all();
        return view('empleado.create', compact('tipos', 'cuentas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ex = DB::table('empleados')
            ->where('user_id', $request->user_id)
            ->where('nombreEm', $request->nombreEm)
            ->where('apellidosEm', $request->apellidosEm)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar porque ya existe el empleado o la cuenta ya usada.');
        }
        $empleados = Empleado::create($request->all());
        return redirect()->back()->with('success', 'Empleado guardado correctamente.');
    }

    public function edit($id)
    {
        $empleado = empleado::find($id);
        $tipos = TipoEmpleado::all();
        $cuentas = User::all();
        return view('empleado.edit', compact('empleado', 'tipos', 'cuentas'));
    }

    public function update(request $request, $id)
    {
        $ex = DB::table('empleados')
            ->where('nombreEm', $request->nombreEm)
            ->where('apellidosEm', $request->apellidosEm)
            ->select(
                'empleados.id_empleado',
                'empleados.user_id'
            )->first();
        //FALTA SI SE CAMBUA DE NOMBRE O APELLDIO Y ESA COMBINACION NO EXISTE
        if ($ex == null) {
            $ve = Empleado::where('user_id', $request->user_id)->exists();
            if ($ve) {
                $ve = Empleado::findorfail($id); //aqui continuar
                if ($ve->user_id == $request->user_id) {

                    $empleado = Empleado::find($id);
                    $empleado->update($request->all());
                    return redirect('/empleado')->with('success', 'Empleado actulizado correctamente.');
                } else {
                    return redirect()->back()->with('error', 'No se puede actulizar la cuenta ya se esta usando.');
                }
            } else {
                $empleado = Empleado::find($id);
                $empleado->update($request->all());
                return redirect('/empleado')->with('success', 'Empleado actulizado correctamente.');
            }
        } else {
            if (($ex->id_empleado == $id) && ($ex->user_id == $request->user_id)) {
                $empleado = Empleado::find($id);
                $empleado->update($request->all());
                return redirect('/empleado')->with('success', 'Empleado actulizado correctamente.');
            } else {
                if (($ex->id_empleado == $id) and ($ex->user_id != $request->user_id)) {
                    $ex = DB::table('empleados')
                        ->where('user_id', $request->user_id)
                        ->exists();
                    if ($ex) {
                        return redirect()->back()->with('error', 'No se puede actulizar la cuenta ya se esta usando.');
                    } else {
                        $empleado = Empleado::find($id);
                        $empleado->update($request->all());
                        return redirect('/empleado')->with('success', 'Empleado actulizado correctamente.');
                    }
                } else {
                    if (($ex->id_empleado != $id) and ($ex->user_id == $request->user_id)) {
                        return redirect()->back()->with('error', 'No se puede actulizar la cuenta ya se esta usando.');
                    } else {
                        return redirect()->back()->with('error', 'No se puede actulizar la cuenta y nombre ya se esta usando.');
                    }
                }
            }
        }
    }

    public function destroy($id)
    {
        $empleado = empleado::find($id);
        if ($empleado->ventas()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociado a unas ventas.');
        }
        $empleado->delete();
        return redirect()->back()->with('success', 'Empleado eliminado correctamente.');
    }
}
