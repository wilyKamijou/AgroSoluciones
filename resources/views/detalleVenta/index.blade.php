@extends('home')

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Detalles De Las Ventas</h2>
<a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
<a href="/detalleVe/crear" class="btn btn-primary"> Crear +</a>
<table class="table table-dark table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Precio unitario</th>
            <th scope="col">Cantidad de producto</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach($detalleVs as $detalleV)

        <tr>
            <td>
                {{$detalleV->id_venta}} - {{$detalleV->id_producto}} - {{$detalleV->id_almacen}}
            </td>
            <td>
                {{$detalleV->precioDv}}

            </td>
            <td>
                {{$detalleV->cantidadDv}}
            </td>
            <td>

                <form action="/detalleVe/{{$detalleV->id_venta}}/{{$detalleV->id_producto}}/{{$detalleV->id_almacen}}/eliminar" method="POST">
                    @CSRF
                    @method('delete')
                    <a href="" class="btn btn-info">Editar</a>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>

            </td>
        </tr>

        @endforeach
    </tbody>
</table>
@endsection