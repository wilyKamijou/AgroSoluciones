<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CuentaController extends Controller
{
    // Mostrar perfil del usuario
    public function perfil()
    {
        $usuario = Auth::user();
        $empleado = Empleado::where('user_id', $usuario->id)->first();
        
        return view('cuenta.perfil', compact('usuario', 'empleado'));
    }

    // Mostrar formulario para cambiar contraseña
    public function cambiarPassword()
    {
        return view('cuenta.cambiar-password');
    }

    // Actualizar contraseña
    public function actualizarPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_actual' => 'required',
            'nuevo_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $usuario = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $usuario->password)) {
            return redirect()->back()
                ->with('error', 'La contraseña actual es incorrecta.')
                ->withInput();
        }

        // Actualizar contraseña
        $usuario->password = Hash::make($request->nuevo_password);
        $usuario->save();

        return redirect()->route('cuenta.perfil')
            ->with('success', 'Contraseña actualizada correctamente.');
    }
}