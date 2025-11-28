@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Empleados</h1>
</section>

<div class="center-wrapper">
    <section class="content">
        <!-- Card de Registrar Empleado -->
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-3">Registrar Nuevo Empleado</h4>

            <form action="/empleado/guardar" method="POST" class="row g-3">
                @csrf

                <!-- Nombre -->
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombreEm" class="form-control" placeholder="Ingrese el nombre del empleado" required>
                </div>

                <!-- Apellidos -->
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidosEm" class="form-control" placeholder="Ingrese los apellidos del empleado" required>
                </div>

                <!-- Sueldo -->
                <div class="col-md-6">
                    <label class="form-label">Sueldo</label>
                    <input type="number" name="sueldoEm" class="form-control" placeholder="Ingrese el sueldo del empleado" required>
                </div>

                <!-- Telefono -->
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="number" name="telefonoEm" class="form-control" placeholder="Ingrese el teléfono del empleado" required>
                </div>

                <!-- Dirección -->
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" placeholder="Ingrese la dirección del empleado" required>
                </div>

                <!-- Tipo de Empleado -->
                <div class="col-md-6">
                    <label class="form-label">Tipo de Empleado</label>
                    <select name="id_tipoE" class="form-control" required>
                        <option value="" disabled selected>Seleccione el tipo de empleado</option>
                        @foreach ($tipos as $tipo)
                        <option value="{{$tipo->id_tipoE}}">{{$tipo->descripcionTip}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cuenta -->
                <div class="col-md-6">
                    <label class="form-label">Cuenta</label>
                    <select name="user_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione la cuenta</option>
                        @foreach ($cuentas as $cuenta)
                        <option value="{{$cuenta->id}}">{{$cuenta->email}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Ventas</h4>

                <div class="d-flex gap-2">

                    <input type="text" class="form-control" placeholder="Buscar por nombre o cliente" style="width: 260px;">

                    <a href="{{ url('/empleado/pdf') }}" class="btn btn-danger d-flex align-items-center gap-1 px-3">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </div>


            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Sueldo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Tipo</th>
                        <th>Cuenta</th>
                        <th>Opciones</th>
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
                            @foreach($tipos as $tipo)
                            @if($tipo->id_tipoE == $empleado->id_tipoE)
                            {{ $tipo->descripcionTip }}
                            @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach($cuentas as $cuenta)
                            @if($cuenta->id == $empleado->user_id)
                            {{ $cuenta->email}}
                            @endif
                            @endforeach
                        </td>
                        <td class="d-flex gap-2">
                            <a href="/empleado/{{$empleado->id_empleado}}/editar" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="/empleado/{{$empleado->id_empleado}}/eliminar" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection