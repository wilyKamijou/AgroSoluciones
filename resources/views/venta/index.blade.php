@extends('home') 

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Ventas</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/venta/crear" class="btn btn-primary"> Crear +</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Fecha</th>
                <th scope="col">Monto Total</th>    
                <th scope="col">empleado que lo registro</th>
                <th scope="col">Cliente</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{$venta->id_venta}}</td>
                <td>{{$venta->fechaVe}}</td>
                <td>{{$venta->montoTotalVe}}</td>
                <td>
                    @foreach($empleados as $empleado)
                        @if ($empleado->id_empleado == $venta->id_empleado)
                            {{$empleado->nombreEm}} {{$empleado->apellidosEm}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($clientes as $cliente) 
                        @if ($cliente ->id_cliente == $venta->id_cliente)
                            {{$cliente ->nombrePr}} {{$cliente->apellidosCl}}
                        @endif
                    @endforeach
                </td>
                <td>
    
                    <form action="/venta/{{$venta->id_venta}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/venta/{{$venta->id_venta}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
