@extends('home')

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Productos</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/producto/crear" class="btn btn-primary"> Crear +</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Nombre tecnico</th>
                <th scope="col">Descripcion</th>
                <th scope="col">Composicion quimica</th>
                <th scope="col">Consentracion quimica</th>
                <th scope="col">Fecha de fabricacion</th>
                <th scope="col">Fecha de vencimiento</th>
                <th scope="col">Unidad de medida</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{$producto->id_producto}}</td>
                <td>{{$producto->nombrePr}}</td>
                <td>{{$producto->nombreTecnico}}</td>
                <td>{{$producto->descripcionPr}}</td>
                <td>{{$producto->compocicionQuimica}}</td>
                <td>{{$producto->consentracionQuimica}}</td>
                <td>{{$producto->fechaFabricacion}}</td>
                <td>{{$producto->fechaVencimiento}}</td>
                <td>{{$producto->unidadMedida}}</td>
                <td>
    
                    <form action="/producto/{{$producto->id_producto}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/producto/{{$producto->id_producto}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
