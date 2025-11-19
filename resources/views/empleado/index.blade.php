@extends('home')

@section('contenido')
<title>Botón con Ícono - Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Empleados</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/empleado/crear" class="btn btn-primary"> Crear +</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Sueldo</th>
                <th scope="col">Telefono</th>
                <th scope="col">Direccion</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
            <tr>
                <td>{{$empleado->id_empleado}}</td>
                <td>{{$empleado->nombreEm}}</td>
                <td>{{$empleado->apellidosEm}}</td>
                <td>{{$empleado->sueldoEm}}</td>
                <td>{{$empleado->telefonoEm}}</td>
                <td>{{$empleado->direccion}}</td>
                <td>
    
                    <form action="/empleado/{{$empleado->id_empleado}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/empleado/{{$empleado->id_empleado}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
