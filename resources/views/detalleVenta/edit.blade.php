@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De Detalle Del Almacen</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">
    <form action="/detalleVe/{{$detalleVe->id_venta}}/{{$detalleVe->id_producto}}/{{$detalleVe->id_almacen}}/actualizar" method="POST" class="compact-form">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row g-3">
            <!-- Venta -->
            <div class="col-md-6">
                <label class="form-label">Venta</label>
                <select name="id_venta" class="form-control" required>
                    <option value="" disabled selected>Seleccione la venta</option>
                    @foreach ($ventas as $venta)
                    @foreach ($clientes as $cliente)
                    @if($venta->id_cliente == $cliente->id_cliente)
                    <option value="{{$venta->id_venta}}">
                        {{$venta->id_venta}} - {{$cliente->nombreCl}} - {{$venta->fechaVe}}
                    </option>
                    @endif
                    @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Almacén y Producto -->
            <div class="col-md-6">
                <label class="form-label">Producto en Almacén</label>
                <select name="idDal" class="form-control" id="productoAlmacenSelect" required>
                    <option value="" disabled selected>Seleccione producto y almacén</option>
                    @foreach($detalleAs as $detalleA)
                    @foreach($productos as $producto)
                    @foreach($almacenes as $almacen)
                    @if (($detalleA->id_almacen == $almacen->id_almacen) and ($detalleA->id_producto == $producto->id_producto))
                    <option value="{{$detalleA->id_producto}}|{{$detalleA->id_almacen}}" data-producto-id="{{$detalleA->id_producto}}" data-precio="{{$producto->precioPr}}">
                        {{$producto->nombrePr}} - {{$almacen->nombreAl}}
                    </option>
                    @endif
                    @endforeach
                    @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Precio -->
            <div class="col-md-4">
                <label class="form-label">Precio Unitario</label>
                <input type="text" step="0.01" name="precioDv" id="precioInput" class="form-control" placeholder="Precio se autocompletará">

            </div>

            <!-- Cantidad -->
            <div class="col-md-4">
                <label class="form-label">Cantidad</label>
                <input type="text" name="cantidadDv" class="form-control" placeholder="Ingrese la cantidad" required>
            </div>

            <!-- Botones -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/detalleVe" class="btn btn-secondary">Cancelar</a>
            </div>
    </form>
</div>

@endsection