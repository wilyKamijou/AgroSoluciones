@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 class="text-center mb-4">Editar Datos De Los Detalles Del Almacen</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">
    <form action="/detalleAl/{{$detalle->id_producto}}/{{$detalle->id_almacen}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row g-3">
            <!-- Producto -->
            <div class="col-md-4">
                <label class="form-label">Producto</label>
                <select name="id_producto" class="form-control" required>
                    <option value="" disabled selected>Seleccione el producto</option>
                    @foreach ($productos as $producto)
                    <option value="{{$producto->id_producto}}">{{$producto->nombrePr}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Almacén -->
            <div class="col-md-4">
                <label class="form-label">Almacén</label>
                <select name="id_almacen" class="form-control" required>
                    <option value="" disabled selected>Seleccione el almacén</option>
                    @foreach ($almacens as $almacen)
                    <option value="{{$almacen->id_almacen}}">{{$almacen->nombreAl}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stock -->
            <div class="col-md-4">
                <label class="form-label">Stock Disponible</label>
                <input type="number" name="stock" class="form-control" value="{{$detalle->stock}}" required>
            </div>

            <!-- Botones -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/detalleAl" class="btn btn-secondary">Cancelar</a>
            </div>
    </form>
</div>

@endsection