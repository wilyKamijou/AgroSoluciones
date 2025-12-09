@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Cliente</h2>
<div class="card shadow-sm p-4 mb-4">
    <form action="/cliente/{{$cliente->id_cliente}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombreCl" class="form-control" value="{{$cliente->nombreCl}}" required>
        </div>

        <!-- Apellidos -->
        <div class="col-md-4">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidosCl" class="form-control" value="{{$cliente->apellidosCl}}" required>
        </div>

        <!-- Teléfono -->
        <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="number" name="telefonoCl" class="form-control" value="{{$cliente->telefonoCl}}" required>
        </div>
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/cliente" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection