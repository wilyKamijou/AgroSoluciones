<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'usuario' => 'required|string',
            'contraseña' => 'required|string',
        ]);
    
        // Obtener las credenciales del formulario
        $credentials = [
            'usuario' => $request->usuario,
            'contraseña' => $request->contraseña
        ];
    
        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Si las credenciales son correctas, redirige a la vista home
            return redirect()->intended('home')->with('success', 'Inicio de sesión exitoso.');
        }
    
        // Si no existe el usuario o la contraseña no es correcta
        return back()->withErrors(['mensaje' => 'Usuario o contraseña incorrectos'])->withInput();
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
