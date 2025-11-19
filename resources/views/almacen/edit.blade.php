@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Almacen</h2>
    <form action="/almacen/{{$almacen->id_almacen}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombreEm" class="form-label">Nombre del almacen:</label>
            <input type="text" id="nombreEm" name="nombreAl" class="form-control" value="{{$almacen->nombreAl}}" required>
        </div>

         <!-- Descripcion -->
         <div class="mb-3">
            <label for="direccion" class="form-label">descripcion del almacen:</label>
            <input type="text" id="direccion" name="descripcionAl" class="form-control" value="{{$almacen->descripcionAl}}" required>
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label for="direccionA" class="form-label">direccion del almacen:</label>
            <input type="text" id="direccionA" name="direccionAl" class="form-control" value="{{$almacen->direccionAl}}" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/almacen" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
