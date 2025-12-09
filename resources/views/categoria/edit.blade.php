@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del La Categoria</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">
    <form action="/categoria/{{$categoria->id_categoria}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="col-md-6">
            <label class="form-label">Nombre de la Categoría</label>
            <input type="text" name="nombreCat" class="form-control" value="{{$categoria->nombreCat}}" required>
        </div>

        <!-- Descripción -->
        <div class="col-md-6">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcionCat" class="form-control" value="{{$categoria->descripcionCat}}" required>
        </div>


        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/categoria" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection