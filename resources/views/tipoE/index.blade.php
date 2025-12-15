@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/rutas.css') }}">
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
                    
        <!-- Sección de Rutas -->
        <div class="mb-3 rutas-dropdown-container">
            <label class="form-label fw-bold">Selecciona Rutas de Acceso:</label>
        
            <!-- Dropdown para seleccionar rutas -->
            <div class="dropdown mb-2">
                <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center" 
                        type="button" 
                        id="dropdownRutas" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                    <span>Elige rutas</span>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-rutas" aria-labelledby="dropdownRutas" style="width: 100%;">
                    <!-- Rutas fijas -->
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="R"
                                name="rutas[]" 
                                id="ruta-reportes"
                                data-nombre="Reportes">
                            <label class="form-check-label" for="ruta-reportes">
                                <strong>Reportes</strong> - Acceso a reportes del sistema
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="U"
                                name="rutas[]" 
                                id="ruta-usuarios"
                                data-nombre="Usuarios">
                            <label class="form-check-label" for="ruta-usuarios">
                                <strong>Usuarios</strong> - Gestión de usuarios del sistema
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="C"
                                name="rutas[]" 
                                id="ruta-clientes"
                                data-nombre="Clientes">
                            <label class="form-check-label" for="ruta-clientes">
                                <strong>Clientes</strong> - Administración de clientes
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="E"
                                name="rutas[]" 
                                id="ruta-empleados"
                                data-nombre="Empleados">
                            <label class="form-check-label" for="ruta-empleados">
                                <strong>Empleados</strong> - Gestión de empleados
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="V"
                                name="rutas[]" 
                                id="ruta-ventas"
                                data-nombre="Ventas">
                            <label class="form-check-label" for="ruta-ventas">
                                <strong>Ventas</strong> - Módulo de ventas
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="A"
                                name="rutas[]" 
                                id="ruta-almacenes"
                                data-nombre="Almacenes">
                            <label class="form-check-label" for="ruta-almacenes">
                                <strong>Almacenes</strong> - Control de almacenes
                            </label>
                        </div>
                    </li>
                    
                    <li class="ruta-option">
                        <div class="form-check">
                            <input class="form-check-input ruta-checkbox" 
                                type="checkbox" 
                                value="P"
                                name="rutas[]" 
                                id="ruta-productos"
                                data-nombre="Productos">
                            <label class="form-check-label" for="ruta-productos">
                                <strong>Productos</strong> - Catálogo de productos
                            </label>
                        </div>
                    </li>
                    </ul>
                    </div>
                    <!-- Contenedor para mostrar las rutas seleccionadas -->
                        <div class="rutas-seleccionadas-container" id="rutasSeleccionadas">
                            <small class="text-muted">No hay rutas seleccionadas</small>
                            </div>
                                <input type="hidden" name="rutas_iniciales" id="rutasInputHidden" value="">
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
                            <th>Rutas permitidas</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipos as $tipo)
                        <tr class="tipo-row">
                            <td>{{$tipo->id_tipoE}}</td>
                            <td>{{$tipo->nombreE}}</td>
                            <td>{{$tipo->descripcionTip}}</td>
                            <td>
                                @php
                                    $rutasCompletas = [
                                        'R' => ['Reportes', 'bg-gradient bg-danger'],
                                        'U' => ['Usuarios', 'bg-gradient bg-success'],
                                        'C' => ['Clientes', 'bg-gradient bg-primary'],
                                        'E' => ['Empleados', 'bg-gradient bg-warning text-dark'],
                                        'V' => ['Ventas', 'bg-gradient bg-info'],
                                        'A' => ['Almacenes', 'bg-gradient bg-secondary'],
                                        'P' => ['Productos', 'bg-gradient bg-dark']
                                    ];
                                    
                                    $iniciales = array_filter(explode(' ', $tipo->rutas_acceso ?? ''));
                                    $countRutas = count($iniciales);
                                @endphp
                                
                                @if($countRutas == 0)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-slash-circle"></i> Sin acceso
                                    </span>
                                @elseif($countRutas == 7)
                                    <span class="badge bg-success bg-gradient">
                                        <i class="bi bi-shield-check"></i> Acceso total
                                    </span>
                                @else
                                    <div class="rutas-compactas">
                                        @foreach($rutasCompletas as $inicial => $info)
                                            @if(in_array($inicial, $iniciales))
                                                <span class="badge {{ $info[1] }} me-1 mb-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $info[0] }}"
                                                    style="font-size: 0.7rem; padding: 0.25em 0.4em;">
                                                    {{ $inicial }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>   
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