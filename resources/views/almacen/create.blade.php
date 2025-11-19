@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Almacen</h2>
    <form action="/almacen/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre del almacen:</label>
            <input type="text" id="nombrePr" name="nombreAl" class="form-control" placeholder="Ingrese el nombre del almacen" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion del almacen:</label>
            <input type="text" id="ubicacionPr" name="descripcionAl" class="form-control" placeholder="Ingrese la descripcion del almacen" requiered>
        </div>

           <!-- Dirección -->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Direccion del almacen:</label>
            <input type="text" id="ubicacion" name="direccionAl" class="form-control" placeholder="Ingrese la dirección del almacen" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/almacen" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
