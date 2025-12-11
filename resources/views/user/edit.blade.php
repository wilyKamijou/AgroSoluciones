@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Usuario</h2>
<div class="card shadow-sm p-4 mb-4">
    <form action="/user/{{$user->id}}/actualizar" method="POST" class="row g-3">
        @method('PUT')

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">


        <!-- Nombre -->
        <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
        </div>

        <!-- Correo -->
        <div class="col-md-4">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" value="{{$user->email}}" required>
        </div>

        <!-- Contraseña -->
        <div class="col-md-4">
            <label class="form-label">Contraseña</label>
            <input type="text" name="password" class="form-control" value="{{$user->password}}" required minlength="8">
        </div>
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/user" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection