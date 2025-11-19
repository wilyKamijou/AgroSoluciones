@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Cliente</h2>
    <form action="/cliente/{{$cliente->id_cliente}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombreCl" class="form-label">Nombre:</label>
            <input type="text" id="nombreCl" name="nombreCl" class="form-control" value="{{$cliente->nombreCl}}" required>
        </div>

        <!-- Apellidos -->
        <div class="mb-3">
            <label for="apellidosCl" class="form-label">Apellidos:</label>
            <input type="text" id="apellidosCl" name="apellidosCl" class="form-control" value="{{$cliente->apellidosCl}}" required>
        </div>

        <!-- Telefono -->
        <div class="mb-3">
            <label for="telefonoCl" class="form-label">Telefono:</label>
            <input type="number" id="telefonoCl" name="telefonoCl" class="form-control" value="{{$cliente->telefonoCl}}" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/cliente" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
