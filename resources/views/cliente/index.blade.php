@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Clientes</h1>
</section>

<div class="center-wrapper">
    <section class="content">
        <!-- Card de Registrar Cliente -->
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-3">Registrar Nuevo Cliente</h4>

            <form action="/cliente/guardar" method="POST" class="row g-3">
                @csrf

                <!-- Nombre -->
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombreCl" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                </div>

                <!-- Apellidos -->
                <div class="col-md-4">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidosCl" class="form-control" placeholder="Ingrese los apellidos del cliente" required>
                </div>

                <!-- Teléfono -->
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="number" name="telefonoCl" class="form-control" placeholder="Ingrese el teléfono del cliente" required>
                </div>

                <!-- Botones -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="/cliente" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- Card Tabla -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Clientes</h4>

                <div class="d-flex gap-2 align-items-center">
                    <!-- Buscador -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o apellido...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>


                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="clientesTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr class="cliente-row">
                            <td><strong>{{$cliente->id_cliente}}</strong></td>
                            <td>{{$cliente->nombreCl}}</td>
                            <td>{{$cliente->apellidosCl}}</td>
                            <td>{{$cliente->telefonoCl}}</td>
                            <td class="d-flex gap-2">
                                <a href="/cliente/{{$cliente->id_cliente}}/editar" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="/cliente/{{$cliente->id_cliente}}/eliminar" method="POST">
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
        </div>
    </section>
</div>
@endsection