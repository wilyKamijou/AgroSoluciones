<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\tipoEmpleado;
use App\Models\Empleado;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/mi-cuenta/perfil';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Verificar si ya hay usuarios registrados
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('error', 'El registro está cerrado. Solo se permite crear el usuario inicial.');
        }
        
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Verificar si ya hay usuarios registrados
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('error', 'No se pueden registrar más usuarios. Contacta al administrador.');
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Crear tipo de empleado y empleado automáticamente
        $this->createInitialEmployeeData($user);

        // Iniciar sesión automáticamente
        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Crear datos iniciales de empleado para el primer usuario
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createInitialEmployeeData(User $user)
    {
        // Crear tipo de empleado "Owner"
        $tipoEmpleado = tipoEmpleado::create([
            'nombreE' => 'Owner',
            'descripcionTip' => 'Dueño del servidor.'
        ]);

        // Crear empleado "Admin" asociado al usuario
        Empleado::create([
            'nombreEm' => 'Admin',
            'apellidosEm' => 'Rojas',
            'sueldoEm' => 999,
            'telefonoEm' => 78123212,
            'direccion' => 'calle falsa123',
            'id_tipoE' => $tipoEmpleado->id_tipoE,
            'user_id' => $user->id
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        // Puedes agregar lógica adicional aquí después del registro
        // Por ejemplo, logs, notificaciones, etc.
        
        return redirect($this->redirectPath())
            ->with('success', '¡Usuario creado exitosamente! Se ha creado automáticamente el perfil de empleado.');
    }
}