@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Proveedor</h2>
    <form action="/proveedor/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre:</label>
            <input type="text" id="nombrePr" name="nombrePr" class="form-control" placeholder="Ingrese el nombre del proveedor" required>
        </div>

        <!-- Telefono -->
        <div class="mb-3">
            <label for="telefonoPr" class="form-label">Telefono:</label>
            <input type="number" id="telefonoPr" name="telefonoPr" class="form-control" placeholder="Ingrese el Telefono del proveedor" required>
        </div>

         <!-- Dirección -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Ubicacion:</label>
            <input type="text" id="ubicacionPr" name="ubicacionPr" class="form-control" placeholder="Ingrese la dirección del Proveedor" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/proveedor" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
