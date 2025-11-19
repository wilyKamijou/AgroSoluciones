@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2  style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Detalle Del Almacen</h2>
    <form action="/detalleAl/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

           <!-- Tipo de producto-->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Elija el producto:</label>
                <select id="id_tipoE" name="id_producto" class="form-select" required>
                    <option value="" disabled selected>Seleccione el producto</option>
                    @foreach ($productos as $producto)
                       <option value={{$producto->id_producto}}> {{$producto->nombrePr}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>

           <!-- Tipo de almacen -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Elija el almacen:</label>
                <select id="id_tipoE" name="id_almacen" class="form-select" required>
                    <option value="" disabled selected>Seleccione el almacen</option>
                    @foreach ($almacens as $almacen)
                       <option value={{$almacen->id_almacen}}> {{$almacen->nombreAl}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>

        
           <!-- fecha -->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Stock disponible del producto:</label>
            <input type="integer" id="ubicacion" name="stock" class="form-control" placeholder="Ingrese el stock" required>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/detalleAl" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
