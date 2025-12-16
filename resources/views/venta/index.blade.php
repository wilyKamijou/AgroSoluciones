@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="/css/custom.css">

<h2 class="text-center mb-4">Gestión de Ventas</h2>

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
                    <input type="text" step="0.01" name="montoTotalVe" class="form-control" placeholder="Ingrese el monto total" required>
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

                <div class="d-flex gap-2 align-items-center">
                    <!-- Buscador -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por empleado, cliente, monto o fecha...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Botones de acción -->
                    <a href="{{ url('/venta/pdf') }}" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="mb-3">
                <small class="text-muted" id="resultCount">
                    Mostrando {{ count($ventas) }} ventas
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="ventasTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Monto Total</th>
                            <th>Empleado</th>
                            <th>Cliente</th>
                            <th style="width: 120px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $venta)
                        @php
                        $empleadoVenta = $empleados->firstWhere('id_empleado', $venta->id_empleado);
                        $clienteVenta = $clientes->firstWhere('id_cliente', $venta->id_cliente);
                        @endphp
                        <tr class="venta-row">
                            <td><strong>{{ $venta->id_venta }}</strong></td>
                            <td>{{ date('d/m/Y', strtotime($venta->fechaVe)) }}</td>
                            <td>{{ number_format($venta->montoTotalVe, 2) }} Bs.</td>
                            <td>
                                @if($empleadoVenta)
                                {{ $empleadoVenta->nombreEm }} {{ $empleadoVenta->apellidosEm }}
                                @else
                                <span class="text-danger">Empleado no encontrado</span>
                                @endif
                            </td>
                            <td>
                                @if($clienteVenta)
                                {{ $clienteVenta->nombreCl }} {{ $clienteVenta->apellidosCl }}
                                @else
                                <span class="text-danger">Cliente no encontrado</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/venta/{{ $venta->id_venta }}/editar" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="/venta/{{ $venta->id_venta }}/eliminar" method="POST">
                                        @csrf
                                        @method('delete')

                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>

</div>

@endsection