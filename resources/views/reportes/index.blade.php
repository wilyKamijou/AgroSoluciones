@extends('home')

@section('contenido')
    <!-- Cargar CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <section class="content-header">
        <h1 class="text-center mb-4"> Reportes del Sistema</h1>
    </section>

    <div class="center-wrapper">
        <section class="content">
            <!-- Card de Filtros -->
            <div class="card shadow-sm p-4 mb-4">
                <h4 class="mb-3"><i class="bi bi-funnel"></i> Filtros de Fecha</h4>

                <form id="filterForm" class="row g-3">
                    <!-- Fecha Inicio -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                            value="{{ date('Y-m-d', strtotime('-1 month')) }}" required>
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="button" onclick="reportes.cargarReporteActivo()" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                        <button type="button" onclick="reportes.generarReporteCompleto()" class="btn btn-success">
                            <i class="bi bi-file-earmark-text"></i> Reporte Completo
                        </button>
                    </div>
                </form>
            </div>

            <!-- Navegación por pestañas -->
            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="productos-cantidad-tab" data-bs-toggle="tab" 
                            data-bs-target="#productos-cantidad" type="button" role="tab">
                        <i class="bi bi-trophy"></i> Productos (Cantidad)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="productos-monto-tab" data-bs-toggle="tab" 
                            data-bs-target="#productos-monto" type="button" role="tab">
                        <i class="bi bi-currency-dollar"></i> Productos (Monto)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="almacenes-tab" data-bs-toggle="tab" 
                            data-bs-target="#almacenes" type="button" role="tab">
                        <i class="bi bi-boxes"></i> Almacenes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="empleados-tab" data-bs-toggle="tab" 
                            data-bs-target="#empleados" type="button" role="tab">
                        <i class="bi bi-people"></i> Empleados
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="vencimientos-tab" data-bs-toggle="tab" 
                            data-bs-target="#vencimientos" type="button" role="tab">
                        <i class="bi bi-clock-history"></i> Vencimientos
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="reportTabsContent">
                <!-- Pestaña 1: Productos por cantidad -->
                <div class="tab-pane fade show active" id="productos-cantidad" role="tabpanel">
                    <div class="card shadow-sm p-4 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-trophy text-primary"></i> Productos Más Vendidos (Cantidad)</h4>
                            <div class="btn-group">
                                <button type="button" onclick="reportes.cargarProductosCantidad()" class="btn btn-primary btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="reportes.exportarPDF('productos-cantidad')" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="button" onclick="reportes.exportarExcel('productos-cantidad')" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                            </div>
                        </div>
                        
                        <div class="chart-container">
                            <canvas id="chartProductosCantidad"></canvas>
                        </div>
                        
                        <div class="table-responsive mt-4" id="tablaProductosCantidadContainer">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Cargando datos...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 2: Productos por monto -->
                <div class="tab-pane fade" id="productos-monto" role="tabpanel">
                    <div class="card shadow-sm p-4 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-currency-dollar text-warning"></i> Productos Más Vendidos (Monto)</h4>
                            <div class="btn-group">
                                <button type="button" onclick="reportes.cargarProductosMonto()" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="reportes.exportarPDF('productos-monto')" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="button" onclick="reportes.exportarExcel('productos-monto')" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                            </div>
                        </div>
                        
                        <div class="chart-container">
                            <canvas id="chartProductosMonto"></canvas>
                        </div>
                        
                        <div class="table-responsive mt-4" id="tablaProductosMontoContainer">
                            <div class="text-center py-3">
                                <div class="spinner-border text-warning" role="status"></div>
                                <p class="mt-2">Cargando datos...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 3: Almacenes -->
                <div class="tab-pane fade" id="almacenes" role="tabpanel">
                    <div class="card shadow-sm p-4 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-boxes text-secondary"></i> Distribución en Almacenes</h4>
                            <div class="btn-group">
                                <button type="button" onclick="reportes.cargarDistribucionAlmacenes()" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="reportes.exportarPDF('almacenes')" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart-container">
                                    <canvas id="chartDistribucionAlmacenes"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="tablaDistribucionAlmacenesContainer">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-secondary" role="status"></div>
                                        <p class="mt-2">Cargando datos...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 4: Empleados -->
                <div class="tab-pane fade" id="empleados" role="tabpanel">
                    <div class="card shadow-sm p-4 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-people text-danger"></i> Empleados con Más Ventas</h4>
                            <div class="btn-group">
                                <button type="button" onclick="reportes.cargarEmpleadosTop()" class="btn btn-danger btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="reportes.exportarPDF('empleados')" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="chart-container">
                            <canvas id="chartEmpleadosTop"></canvas>
                        </div>
                        
                        <div class="table-responsive mt-4" id="tablaEmpleadosTopContainer">
                            <div class="text-center py-3">
                                <div class="spinner-border text-danger" role="status"></div>
                                <p class="mt-2">Cargando datos...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 5: Vencimientos -->
                <div class="tab-pane fade" id="vencimientos" role="tabpanel">
                    <div class="card shadow-sm p-4 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="mb-1"><i class="bi bi-clock-history text-info"></i> Productos por Vencer</h4>
                                <p class="text-muted mb-0">Control de vencimientos de productos</p>
                            </div>
                            <div class="btn-group">
                                <button type="button" onclick="reportes.cargarProductosVencimiento()" class="btn btn-info btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="reportes.exportarPDF('vencimientos')" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="button" onclick="reportes.exportarExcel('vencimientos')" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                            </div>
                        </div> 
                        
                        <!-- Filtro simple -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="number" id="diasLimite" class="form-control" value="30" min="1" max="365" 
                                        placeholder="Días para alerta">
                                    <button class="btn btn-primary" type="button" onclick="reportes.cargarProductosVencimiento()">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                </div>
                                <small class="text-muted">Mostrar productos que vencen en los próximos X días</small>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-light border d-flex align-items-center h-100">
                                    <i class="bi bi-info-circle me-2 text-info"></i>
                                    <small>Los productos vencidos se muestran automáticamente (últimos 90 días)</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contador de estado -->
                        <div class="row mb-4" id="contadorVencimientos">
                            <div class="col-md-12 text-center">
                                <div class="spinner-border text-info" role="status"></div>
                                <p class="mt-2">Cargando estado...</p>
                            </div>
                        </div>
                        
                        <!-- Lista simple de productos por vencer -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-3">
                                <h5 class="mb-3">
                                    <i class="bi bi-clock text-warning"></i> 
                                    Próximos a Vencer
                                    <span class="badge bg-warning float-end" id="contadorProximos">0</span>
                                </h5>
                                <div id="listaProximos" class="list-group list-group-flush">
                                    <div class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                        <p class="mt-2 text-muted">Cargando productos próximos a vencer...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lista simple de productos vencidos -->
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <h5 class="mb-3">
                                    <i class="bi bi-exclamation-triangle text-danger"></i> 
                                    Productos Vencidos
                                    <span class="badge bg-danger float-end" id="contadorVencidos">0</span>
                                </h5>
                                <div id="listaVencidos" class="list-group list-group-flush">
                                    <div class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                                        <p class="mt-2 text-muted">Cargando productos vencidos...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mensaje de estado -->
                        <div class="alert alert-success mt-4 d-none" id="mensajeTodoBien">
                            <i class="bi bi-check-circle"></i>
                            <strong>¡Excelente!</strong> No hay productos próximos a vencer ni vencidos.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Reporte Completo -->
            <div class="modal fade" id="reporteCompletoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-graph-up"></i> Reporte Completo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="reporteCompletoContent">
                            <div class="text-center py-5">
                                <div class="spinner-border" role="status"></div>
                                <p class="mt-2">Generando reporte...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="reportes.imprimirReporte()">
                                <i class="bi bi-printer"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/reports.js') }}"></script>
@endsection