@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Proveedor</h2>
    <form action="/proveedor/{{$proveedor->id_proveedor}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombreEm" class="form-label">Nombre:</label>
            <input type="text" id="nombreEm" name="nombrePr" class="form-control" value="{{$proveedor->nombrePr}}" required>
        </div>

        <!-- Telefono -->
        <div class="mb-3">
            <label for="telefonoEm" class="form-label">Telefono:</label>
            <input type="number" id="telefonoEm" name="telefonoPr" class="form-control" value="{{$proveedor->telefonoPr}}" required>
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label for="direccion" class="form-label">Ubicacion:</label>
            <input type="text" id="direccion" name="ubicacionPr" class="form-control" value="{{$proveedor->ubicacionPr}}" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/proveedor" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
