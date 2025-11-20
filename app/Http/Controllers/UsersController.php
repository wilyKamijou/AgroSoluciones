<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = user::create($request->all());
        return redirect('/user');
    }

    /**
     * Display the specified resource.
     */
    public function show($resenas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = user::findorFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $user = user::findorFail($id);
        $user->update($request->all());
        return redirect('/user');
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
        $user = user::findorfail($id);
        $user->delete();
        return redirect('/user');
    }
}
