<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tipoEmpleadoId = $user->empleado->id_tipoE;

        $menuRutas = DB::table('dt_rutas')
            ->join('rutas', 'dt_rutas.id_ruta', '=', 'rutas.id_ruta')
            ->where('dt_rutas.id_tipoE', $tipoEmpleadoId)
            ->select('rutas.nombreR', 'rutas.url')
            ->get();

        return view('dashboard', compact('menuRutas'));
    }
}
