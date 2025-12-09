@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">
<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De Un Tipo De Empleado</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">

    <form action="/tipo/{{$tipo->id_tipoE}}/actualizar" method="POST" class="compact-form">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row g-3">
            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Nombre del Tipo</label>
                <input type="text" name="nombreE" class="form-control" value="{{$tipo->nombreE}}" required>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Descripción del Tipo</label>
                <input type="text" name="descripcionTip" class="form-control" value="{{$tipo->descripcionTip}}" required>
            </div>

            <!-- Botones -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/tipo" class="btn btn-secondary">Cancelar</a>
            </div>
    </form>
</div>

@endsection