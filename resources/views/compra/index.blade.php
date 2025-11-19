@extends('home') 

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Compras</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/compra/crear" class="btn btn-primary"> Crear +</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Fecha</th>
                <th scope="col">Monto Total</th>    
                <th scope="col">empleado que lo registro</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
            <tr>
                <td>{{$compra->id_compra}}</td>
                <td>{{$compra->fechaCo}}</td>
                <td>{{$compra->montoTotalCo}}</td>
                <td>
                    @foreach($empleados as $empleado)
                        @if ($empleado->id_empleado == $compra->id_empleado)
                            {{$empleado->nombreEm}} {{$empleado->apellidosEm}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($proveedors as $proveedor)
                        @if ($proveedor->id_proveedor == $compra->id_proveedor)
                            {{$proveedor->nombrePr}}
                        @endif
                    @endforeach
                </td>
                <td>
    
                    <form action="/compra/{{$compra->id_compra}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/compra/{{$compra->id_compra}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
