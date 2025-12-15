@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/rutas.css') }}">
<script src="/js/rutas.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/rutas.js"></script>

<section class="content-header">
    <h1 class="text-center mb-4">Editar Tipo de Empleado</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Editar Tipo Empleado -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Editar Tipo: {{ $tipo->nombreE }}</h4>

            <form action="/tipo/{{ $tipo->id_tipoE }}/actualizar" method="POST" class="compact-form">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Tipo</label>
                        <input type="text" name="nombreE" class="form-control" value="{{ $tipo->nombreE }}" required>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-6">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" value="{{ $tipo->descripcionTip }}" required>
                    </div>
                    
                    <!-- Sección de Rutas -->
                    <div class="col-12">
                        <div class="mb-3 rutas-dropdown-container">
                            <label class="form-label fw-bold">Selecciona Rutas de Acceso:</label>
                        
                            <!-- Dropdown para seleccionar rutas -->
                            <div class="dropdown mb-2">
                                <button class="btn btn-outline-secondary  w-100 d-flex justify-content-between align-items-center" 
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
                            
                            <!-- Input oculto para las iniciales -->
                            <input type="hidden" name="rutas_iniciales" id="rutasInputHidden" value="{{ $tipo->rutas_acceso }}">
                            
                            <!-- Pequeña ayuda -->
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle"></i> Las rutas actuales son: 
                                @php
                                    $mapaRutas = [
                                        'R' => 'Reportes',
                                        'U' => 'Usuarios', 
                                        'C' => 'Clientes',
                                        'E' => 'Empleados',
                                        'V' => 'Ventas',
                                        'A' => 'Almacenes',
                                        'P' => 'Productos'
                                    ];
                                    $iniciales = explode(' ', $tipo->rutas_acceso ?? '');
                                    $nombresRutas = [];
                                    foreach ($iniciales as $inicial) {
                                        if (isset($mapaRutas[$inicial])) {
                                            $nombresRutas[] = $mapaRutas[$inicial];
                                        }
                                    }
                                @endphp
                                {{ implode(', ', $nombresRutas) ?: 'Ninguna' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar
                            </button>
                            <a href="/tipo" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection