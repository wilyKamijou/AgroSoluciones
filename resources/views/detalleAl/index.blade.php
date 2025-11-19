@extends('home') 

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Detalles De los Almacenes</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/detalleAl/crear" class="btn btn-primary"> Crear +</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre del producto</th>
                <th scope="col">Categoria del producto</th>    
                <th scope="col">Almacen del producto</th>
                <th scope="col">Stock del producto</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{$detalle->id_producto}}-{{$detalle->id_almacen}}</td>
                <td>
                    @foreach($productos as $producto)
                        @if ($producto->id_producto == $detalle->id_producto)
                            {{$producto->nombrePr}} 
                        @endif
                    @endforeach
                </td>

                <td>
                    @foreach($productos as $producto)
                        @if ($producto->id_producto == $detalle->id_producto)
                            {{$producto->id_categoria}} 
                        @endif
                    @endforeach
                </td>

                <td>
                    @foreach($almacens as $almacen)
                        @if ($almacen->id_almacen == $detalle->id_almacen)
                            {{$almacen->nombreAl}}
                        @endif
                    @endforeach
                </td>
                <td>{{$detalle->stock}}</td>
                <td>
                
                    <form action="/detalleAl/{{$detalle->id_producto}}/{{$detalle->id_almacen}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/detalleAl/{{$detalle->id_producto}}/{{$detalle->id_almacen}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
