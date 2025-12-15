@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 class="text-center mb-4">Editar Datos Del Almacen</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">
    <form action="/almacen/{{$almacen->id_almacen}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="col-md-4">
            <label class="form-label">Nombre del Almacén</label>
            <input type="text" name="nombreAl" class="form-control" value="{{$almacen->nombreAl}}" required>
        </div>

        <!-- Descripción -->
        <div class="col-md-4">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcionAl" class="form-control" value="{{$almacen->descripcionAl}}" required>
        </div>

        <!-- Dirección -->
        <div class="col-md-4">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccionAl" class="form-control" value="{{$almacen->direccionAl}}" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/almacen" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection