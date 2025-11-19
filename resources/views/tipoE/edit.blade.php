@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De Un Tipo De Empleado</h2>
    <form action="/tipo/{{$tipo->id_tipoE}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombreEm" class="form-label">Descripcion del tipo de empleado:</label>
            <input type="text" id="nombreEm" name="descripcionTip" class="form-control" value="{{$tipo->descripcionTip}}" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/tipo" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
