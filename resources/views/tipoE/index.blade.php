@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="/js/rutas.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Tipos de Empleado</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Tipo Empleado (Más compacta) -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Tipo de Empleado</h4>

            <form action="/tipo/guardar" method="POST" class="compact-form">
                @csrf

                <div class="row g-3">
                    <!-- Descripción -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Tipo</label>
                        <input type="text" name="nombreE" class="form-control" placeholder="Ingrese tipo de puesto" required>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-6">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" placeholder="Ingrese la descripción del tipo de empleado" required>
                    </div>
                    <!--rutas-->
                    <div class="mb-3">
                        <label class="form-label">Selecciona Rutas:</label>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownRutas" data-bs-toggle="dropdown" aria-expanded="false">
                                Elige rutas
                            </button>
                            <ul class="dropdown-menu p-3" aria-labelledby="dropdownRutas" style="max-height: 200px; overflow-y: auto;">
                                @foreach($rutas as $ruta)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input ruta-checkbox" type="checkbox" value="{{ $ruta->id_ruta }}" name="rutas[]" id="ruta-{{ $ruta->id_ruta }}">
                                        <label class="form-check-label" for="ruta-{{ $ruta->id_ruta }}">
                                            {{ $ruta->nombreR }}
                                        </label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>

                    </div>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Tipos de Empleado</h4>

                <div class="d-flex gap-2 align-items-center">
                    <!-- Buscador -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por descripción...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="mb-3">
                <small class="text-muted" id="resultCount">
                    Mostrando {{ count($tipos) }} tipos de empleado
                </small>
            </div>

            <div class="table-container-small">
                <table class="table table-hover table-small cols-4" id="tiposTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipos as $tipo)
                        <tr class="tipo-row">
                            <td>{{$tipo->id_tipoE}}</td>
                            <td>{{$tipo->nombreE}}</td>
                            <td>{{$tipo->descripcionTip}}</td>
                            <td class="d-flex gap-2">
                                <a href="/tipo/{{$tipo->id_tipoE}}/editar" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/tipo/{{$tipo->id_tipoE}}/eliminar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este tipo de empleado?')">
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