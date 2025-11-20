@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Detalle De Venta</h2>
<form action="/detalleVe/guardar" method="POST">

    <!-- CSRF Token (Laravel) -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

       <!-- venta -->
    <div class="mb-3">
        <label for="id_tipoE" class="form-label">Elija la venta:</label>
        <select id="id_tipoE" name="id_venta" class="form-select" required>
            <option value="" disabled selected>Seleccione la venta</option>
            @foreach ($ventas as $venta)
                @foreach ($clientes as $cliente)
                    @if($venta->id_cliente==$cliente->id_cliente)
                        <option value={{$venta->id_venta}}> {{$venta->id_venta}} - {{$cliente->nombreCl}} - {{$venta->fechaVe}} </option>
                    @endif
                @endforeach
            @endforeach

        </select>
    </div>
 

    <!-- Almacen-->
    <div class="mb-3">
        <label for="ubicacion" class="form-label">Detalle de Almacen:</label>
        <select id="id_tipoE" name="idDal" class="form-select" required>
            <option value="" disabled selected>Seleccione detalle del almacen</option>
            @foreach($detalleAs as $detalleA)
                @foreach($productos as $producto)
                    @foreach($almacenes as $almacen)
                            @if (($detalleA->id_almacen==$almacen->id_almacen) and ($detalleA->id_producto==$producto->id_producto))
                                <option value="{{$detalleA->id_producto}}|{{$detalleA->id_almacen}}"> {{$producto->nombrePr}}-{{$almacen->nombreAl}} </option>
                            @endif
                    @endforeach
                @endforeach
            @endforeach
        </select>
    </div>

    <!-- precio -->
    <div class="mb-3">
        <label for="ubicacion" class="form-label">Precio del prodcuto:</label>
        <input type="float" id="ubicacion" name="precioDv" class="form-control" placeholder="Ingrese el precio" required>
    </div>


    <!-- cantidad -->
    <div class="mb-3">
        <label for="ubicacion" class="form-label">Cantidad del producto:</label>
        <input type="integer" id="ubicacion" name="cantidadDv" class="form-control" placeholder="Ingrese la cantidad" required>
    </div>

    <!-- Botones -->
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="/detalleVe" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection