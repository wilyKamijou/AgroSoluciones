<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function generateFakeUsers(Request $request)
    {
        // Validar cantidad de usuarios a generar
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:100'
        ]);

        $faker = Faker::create();

        // Roles disponibles (puedes ajustar según necesites)
        $roles = ['cliente', 'admin'];

        // Generar los usuarios
        for ($i = 0; $i < $validated['count']; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Contraseña por defecto
                'role' => 'usuario',
                'email_verified_at' => now(), // Verificar email automáticamente
            ]);
        }

        return redirect()->back()
            ->with('success', "Se generaron {$validated['count']} usuarios de prueba correctamente");
    }

    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }


    public function create()
    {
        return view('user.create');
    }


    public function store(Request $request)
    {
        $ex = DB::table('users')
            ->where('name', $request->name)
            ->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede guardar porque el nombre ya existe.');
        } else {
            $ex = DB::table('users')
                ->where('email', $request->email)
                ->exists();
            if ($ex) {
                return redirect()->back()->with('error', 'No se puede guardar porque el correo ya existe.');
            }
            $user = user::create($request->all());
            return redirect()->back()->with('success', 'Cuenta se guardada correctamente.');
        }
    }

    public function edit($id)
    {
        $user = user::findorFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request,  $id)
    {
        $ex = DB::table('users')
            ->where('name', $request->name)
            ->select(
                'email',
                'id'
            )->first();
        if ($ex == null) {
            $co = DB::table('users')
                ->where('email', $request->email)
                ->select(
                    'email',
                    'id'
                )->first();

            if ($co != null and $co->id != $id) {
                return redirect()->back()->with('error', 'No se puede actualizar porque el correo ya se esta usando.');
            }
            $us = User::find($id);
            $us->update($request->all());
            return redirect('/user')->with('success', 'Cuenta actualizada correctamente.');
        } else {

            if ($ex->id == $id and $ex->email != $request->email) {
                $co = DB::table('users')
                    ->where('email', $request->email)
                    ->exists();
                if ($co) {
                    return redirect()->back()->with('error', 'No se puede actualizar porque el correo ya se esta usando.');
                }
                $us = User::find($id);
                $us->update($request->all());
                return redirect('/user')->with('success', 'Cuenta actualizada correctamente.');
            } else {
                return redirect()->back()->with('error', 'No se puede actualizar porque el nombre de usuario ya se esta usando.');
            }
        }
    }
    public function cambiarRol($id)
    {
        $user = User::findOrFail($id);
        $user->role = $user->role === 'admin' ? 'cliente' : 'admin';
        $user->save();

        return redirect()->back()->with('success', 'Rol cambiado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ex = Empleado::where('user_id', $id)->exists();
        if ($ex) {
            return redirect()->back()->with('error', 'No se puede eliminar porque esta asociado a un empleado.');
        }
        $user = user::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'Cuenta se eliminado correctamente.');
    }
}
