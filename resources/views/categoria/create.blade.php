@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Categoria</h2>
    <form action="/categoria/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre de la categoria:</label>
            <input type="text" id="nombrePr" name="nombreCat" class="form-control" placeholder="Ingrese el nombre de la categoria" required>
        </div>

         <!-- Dirección -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion de la categoria:</label>
            <input type="text" id="ubicacionPr" name="descripcionCat" class="form-control" placeholder="Ingrese la dirección de la categoria" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/categoria" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
