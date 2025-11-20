@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Usuario</h2>
<form action="/user/{{$user->id}}/actualizar" method="POST">
    @method('PUT')

    <!-- CSRF Token (Laravel) -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <!-- Nombre -->
    <div class="mb-3">
        <label for="nombrePr" class="form-label">Nombre del usuario:</label>
        <input type="text" id="nombre" name="name" class="form-control" value="{{$user->name}}" required>
    </div>

    <!-- Descripcion -->
    <div class="mb-3">
        <label for="ubicacionPr" class="form-label">Correo del usuario:</label>
        <input type="text" id="ubicacionPr" name="email" class="form-control" value="{{$user->email}}" requiered>
    </div>

    <!-- precio-->
    <div class="mb-3">
        <label for="ubicacion" class="form-label">Contraseña del usuario:</label>
        <input type="text" id="ubicacion" name="password" class="form-control" value="{{$user->password}}" required>
    </div>

    <!-- Botones -->
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="/user" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection