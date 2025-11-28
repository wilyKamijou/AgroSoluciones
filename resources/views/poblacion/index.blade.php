@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    .btn-poblar {
        margin: 5px 0;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        text-align: left;
    }
    .btn-poblar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Colores personalizados para los botones */
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
        color: white;
    }
    
    .btn-orange {
        background-color: #fd7e14;
        border-color: #fd7e14;
        color: white;
    }
    .btn-orange:hover {
        background-color: #e56a00;
        border-color: #e56a00;
        color: white;
    }
    
    .btn-teal {
        background-color: #20c997;
        border-color: #20c997;
        color: white;
    }
    .btn-teal:hover {
        background-color: #1aa179;
        border-color: #1aa179;
        color: white;
    }
    
    .btn-indigo {
        background-color: #6610f2;
        border-color: #6610f2;
        color: white;
    }
    .btn-indigo:hover {
        background-color: #520dc2;
        border-color: #520dc2;
        color: white;
    }
    
    .btn-pink {
        background-color: #d63384;
        border-color: #d63384;
        color: white;
    }
    .btn-pink:hover {
        background-color: #b02a6b;
        border-color: #b02a6b;
        color: white;
    }
    
    .btn-cyan {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: white;
    }
    .btn-cyan:hover {
        background-color: #0ba5c7;
        border-color: #0ba5c7;
        color: white;
    }
    
    /* Colores para los badges */
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
    .bg-teal { background-color: #20c997 !important; }
    .bg-indigo { background-color: #6610f2 !important; }
    .bg-pink { background-color: #d63384 !important; }
    .bg-cyan { background-color: #0dcaf0 !important; }

    .step-indicator {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }
    
    .step-number {
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 12px;
    }
    
    .status-card {
        border-left: 4px solid #28a745;
    }
    
    .action-card {
        border-left: 4px solid #dc3545;
    }
</style>

<section class="content-header">
    <h1 class="text-center mb-4">Población de Base de Datos - AgroQuímicos</h1>
</section>

<div class="center-wrapper">
    <section class="content">
        <!-- Alertas -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mx-3" role="alert">
            <i class="bi bi-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Indicador de pasos -->
   <div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="card-title">
            <div class="fw-bold h5"><i class="bi bi-list-ol"></i> Orden recomendado <br>     de poblacion</div> 
        </div>
        <div class="step-indicator">
            <div class="row">
                <div class="col-md-6">
                    <ol class="list-unstyled">
                        <li class="mb-2"><span class="step-number">1</span> Tipos de Empleado</li>
                        <li class="mb-2"><span class="step-number">2</span> Usuarios</li>
                        <li class="mb-2"><span class="step-number">3</span> Empleados</li>
                        <li class="mb-2"><span class="step-number">4</span> Clientes</li>
                        <li class="mb-2"><span class="step-number">5</span> Categorías</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <ol class="list-unstyled" start="6">
                        <li class="mb-2"><span class="step-number">6</span> Productos</li>
                        <li class="mb-2"><span class="step-number">7</span> Almacenes</li>
                        <li class="mb-2"><span class="step-number">8</span> Detalle Almacén</li>
                        <li class="mb-2"><span class="step-number">9</span> Ventas</li>
                        <li class="mb-2"><span class="step-number">10</span> Detalle Venta</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="row">
            <!-- Columna de modelos individuales -->
<div class="row">
    <!-- Columna principal (70%) -->
    <div class="col-md-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-database-add"></i> Población Individual por Modelos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">  
                                <form action="{{ route('poblacion.tipo-empleado') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-poblar w-100">
                                        <i class="bi bi-person-badge"></i> Tipos de Empleado (6 roles)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.usuarios') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-poblar w-100">
                                        <i class="bi bi-people"></i> Usuarios (12 usuarios)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.empleado') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-poblar w-100">
                                        <i class="bi bi-person-gear"></i> Empleados (12 empleados)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.cliente') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-poblar w-100">
                                        <i class="bi bi-building"></i> Clientes (30 clientes)
                                    </button>
                                </form>
                                
                                <form action="{{ route('poblacion.categorias') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-purple btn-poblar w-100">
                                        <i class="bi bi-tags"></i> Categorías (15 categorías)
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('poblacion.productos') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-orange btn-poblar w-100">
                                        <i class="bi bi-flask"></i> Productos (30 productos)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.almacenes') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-teal btn-poblar w-100">
                                        <i class="bi bi-house-door"></i> Almacenes (5 almacenes)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.detalle-almacen') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-indigo btn-poblar w-100">
                                        <i class="bi bi-box-seam"></i> Detalle Almacén (Lógica inteligente)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.ventas') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-pink btn-poblar w-100">
                                        <i class="bi bi-cash-coin"></i> Ventas (50 ventas)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.detalle-venta') }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-cyan btn-poblar w-100">
                                        <i class="bi bi-receipt"></i> Detalle Venta
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna de acciones masivas y estado -->
                <div class="col-md-5">
        <!-- Acciones Masivas -->
        <div class="card shadow-sm mb-3 action-card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0"><i class="bi bi-lightning"></i> Acciones Masivas</h6>
            </div>
            <div class="card-body py-2">
                <form action="{{ route('poblacion.todo') }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-rocket"></i> Poblar Todo
                    </button>
                </form>
                <form action="{{ route('poblacion.limpiar') }}" method="POST" 
                      onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-trash"></i> Limpiar Datos
                    </button>
                </form>
            </div>
        </div>

        <!-- Estado actual más compacto -->
        <div class="card shadow-sm status-card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0"><i class="bi bi-graph-up"></i> Estado BD</h6>
            </div>
            <div class="card-body py-2">
                <div class="row small">
                    <div class="col-6 mb-1">
                        <span>Tipos Emp:</span>
                        <span class="badge bg-primary rounded-pill">{{ $counts['tipoEmpleado'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Usuarios:</span>
                        <span class="badge bg-secondary rounded-pill">{{ $counts['usuarios'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Empleados:</span>
                        <span class="badge bg-info rounded-pill">{{ $counts['empleados'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Clientes:</span>
                        <span class="badge bg-warning rounded-pill">{{ $counts['clientes'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Categorías:</span>
                        <span class="badge bg-purple rounded-pill">{{ $counts['categorias'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Productos:</span>
                        <span class="badge bg-orange rounded-pill">{{ $counts['productos'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Almacenes:</span>
                        <span class="badge bg-teal rounded-pill">{{ $counts['almacenes'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Det. Alm:</span>
                        <span class="badge bg-indigo rounded-pill">{{ $counts['detalleAlmacenes'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Ventas:</span>
                        <span class="badge bg-pink rounded-pill">{{ $counts['ventas'] }}</span>
                    </div>
                    <div class="col-6 mb-1">
                        <span>Det. Vent:</span>
                        <span class="badge bg-cyan rounded-pill">{{ $counts['detalleVentas'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </section>
</div>

@endsection