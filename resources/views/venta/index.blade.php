@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="/css/custom.css">
    <section class="content-header">
        <h1 class="text-center mb-4">Gestión de Ventas</h1>
    </section>
<div class="center-wrapper">



    <section class="content">

        <!-- Card de Registrar Venta -->
        <div class="card shadow-sm p-4 mb-4">
    <h4 class="mb-3">Registrar Venta</h4>

    <form action="/venta/guardar" method="POST" class="row g-3">
        @csrf

        <!-- Fecha -->
        <div class="col-md-6">
            <label class="form-label">Fecha de la venta</label>
            <input type="date" name="fechaVe" class="form-control" required>
        </div>

        <!-- Monto total -->
        <div class="col-md-6">
            <label class="form-label">Monto Total</label>
            <input type="number" step="0.01" name="montoTotalVe" class="form-control" placeholder="Ingrese el monto total" required>
        </div>

        <!-- Cliente -->
        <div class="col-md-6">
            <label class="form-label">Cliente de la venta</label>
            <select name="id_cliente" class="form-control" required>
                <option value="" disabled selected>Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id_cliente }}">
                        {{ $cliente->nombreCl }} {{ $cliente->apellidosCl }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Empleado -->
        <div class="col-md-6">
            <label class="form-label">Empleado que registró la venta</label>
            <select name="id_empleado" class="form-control" required>
                <option value="" disabled selected>Seleccione un empleado</option>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->id_empleado }}">
                        {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botón Guardar -->
        <div class="col-md-3 mt-3">
            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </div>

    </form>

</div>


        <!-- Card Tabla -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Ventas</h4>

                <div class="d-flex gap-2">

                    <input type="text" class="form-control" placeholder="Buscar por nombre o cliente" style="width: 260px;">

                    <a href="{{ url('/venta/pdf') }}" class="btn btn-danger d-flex align-items-center gap-1 px-3">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Monto Total</th>
                        <th>Empleado</th>
                        <th>Cliente</th>
                        <th>Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id_venta }}</td>
                        <td>{{ $venta->fechaVe }}</td>
                        <td>{{ $venta->montoTotalVe }}</td>

                        <td>
                            @foreach($empleados as $empleado)
                                @if($empleado->id_empleado == $venta->id_empleado)
                                    {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                                @endif
                            @endforeach
                        </td>

                        <td>
                            @foreach($clientes as $cliente)
                                @if($cliente->id_cliente == $venta->id_cliente)
                                    {{ $cliente->nombreCl }} {{ $cliente->apellidosCl }}
                                @endif
                            @endforeach
                        </td>

                        <td class="d-flex gap-2">

                            <a href="/venta/{{ $venta->id_venta }}/editar"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="/venta/{{ $venta->id_venta }}/eliminar" method="POST">
                                @csrf
                                @method('delete')

                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
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
