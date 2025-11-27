<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Población de Datos - AgroQuímicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-poblar {
            margin: 8px;
            padding: 12px 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .btn-poblar:hover {
            transform: translateY(-2px);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .step-indicator {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .step-number {
            background: #007bff;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        /* Colores personalizados para los nuevos botones */
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
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        .bg-orange {
            background-color: #fd7e14 !important;
        }
        .bg-teal {
            background-color: #20c997 !important;
        }
        .bg-indigo {
            background-color: #6610f2 !important;
        }
        .bg-pink {
            background-color: #d63384 !important;
        }
        .bg-cyan {
            background-color: #0dcaf0 !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="text-center mb-0">Población de Base de Datos - AgroQuímicos</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ✅ {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ❌ {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                ℹ️ {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Indicador de pasos actualizado -->
                        <div class="step-indicator">
                            <h5>📋 Orden recomendado de población:</h5>
                            <ol class="list-unstyled">
                                <li class="mb-2"><span class="step-number">1</span> Tipos de Empleado</li>
                                <li class="mb-2"><span class="step-number">2</span> Usuarios</li>
                                <li class="mb-2"><span class="step-number">3</span> Empleados</li>
                                <li class="mb-2"><span class="step-number">4</span> Clientes</li>
                                <li class="mb-2"><span class="step-number">5</span> Categorías</li>
                                <li class="mb-2"><span class="step-number">6</span> Productos</li>
                                <li class="mb-2"><span class="step-number">7</span> Almacenes</li>
                                <li class="mb-2"><span class="step-number">8</span> Detalle Almacén</li>
                                <li class="mb-2"><span class="step-number">9</span> Ventas</li>
                                <li class="mb-2"><span class="step-number">10</span> Detalle Venta</li>
                            </ol>
                        </div>

                        <div class="row">
                            <!-- Columna de modelos individuales -->
                            <div class="col-md-6">
                                <h5 class="text-center mb-4">Población Individual</h5>
                                
                                <form action="{{ route('poblacion.tipo-empleado') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-poblar w-100">
                                        📊 Poblar Tipos de Empleado (6 roles)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.usuarios') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-poblar w-100">
                                        👤 Poblar Usuarios (12 usuarios)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.empleado') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-poblar w-100">
                                        👥 Poblar Empleados (12 empleados)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.cliente') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-poblar w-100">
                                        🏪 Poblar Clientes (30 clientes)
                                    </button>
                                </form>
                                
                                <form action="{{ route('poblacion.categorias') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-purple btn-poblar w-100">
                                        🏷️ Poblar Categorías (15 categorías)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.productos') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-orange btn-poblar w-100">
                                        🧪 Poblar Productos (30 productos)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.almacenes') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-teal btn-poblar w-100">
                                        🏭 Poblar Almacenes (5 almacenes)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.detalle-almacen') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-indigo btn-poblar w-100">
                                        📦 Poblar Detalle Almacén (Lógica inteligente)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.ventas') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-pink btn-poblar w-100">
                                        💰 Poblar Ventas (50 ventas)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.detalle-venta') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-cyan btn-poblar w-100">
                                        🧾 Poblar Detalle Venta
                                    </button>
                                </form>
                            </div>

                            <!-- Columna de acciones masivas -->
                            <div class="col-md-6">
                                <h5 class="text-center mb-4">Acciones Masivas</h5>

                                <form action="{{ route('poblacion.todo') }}" method="POST" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-poblar w-100">
                                        🚀 Poblar Todo (Automático)
                                    </button>
                                </form>

                                <form action="{{ route('poblacion.limpiar') }}" method="POST" class="text-center mb-3" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar todos los datos? Esta acción no se puede deshacer.')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-poblar w-100">
                                        🗑️ Limpiar Todos los Datos
                                    </button>
                                </form>

                                <!-- Estado actual -->  
                                <div class="mt-4">
                                    <h6>Estado actual de la base de datos:</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Tipos de Empleado
                                            <span class="badge bg-primary rounded-pill">{{ $counts['tipoEmpleado'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Usuarios
                                            <span class="badge bg-secondary rounded-pill">{{ $counts['usuarios'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Empleados
                                            <span class="badge bg-info rounded-pill">{{ $counts['empleados'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Clientes
                                            <span class="badge bg-warning rounded-pill">{{ $counts['clientes'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Categorías
                                            <span class="badge bg-purple rounded-pill">{{ $counts['categorias'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Productos
                                            <span class="badge bg-orange rounded-pill">{{ $counts['productos'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Almacenes
                                            <span class="badge bg-teal rounded-pill">{{ $counts['almacenes'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Detalle Almacenes
                                            <span class="badge bg-indigo rounded-pill">{{ $counts['detalleAlmacenes'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ventas
                                            <span class="badge bg-pink rounded-pill">{{ $counts['ventas'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Detalle Ventas
                                            <span class="badge bg-cyan rounded-pill">{{ $counts['detalleVentas'] }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>