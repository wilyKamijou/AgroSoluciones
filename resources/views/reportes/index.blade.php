    @extends('home')

    @section('contenido')

    <!-- Cargar CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .report-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .progress {
            height: 20px;
        }
        .nav-tabs .nav-link {
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
        }
        .tab-content {
            padding: 20px 0;
        }
        /* Estilos para la versión simple */
        .list-group-item {
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }
        .list-group-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transform: translateX(2px);
        }
        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 20px;
        }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #000 !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-dark { background-color: #343a40 !important; }
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        .card-title {
            font-weight: 600;
        }
    </style>

    <section class="content-header">
        <h1 class="text-center mb-4">📊 Reportes del Sistema</h1>
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
                        <button type="button" onclick="cargarReporteActivo()" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                        <button type="button" onclick="generarReporteCompleto()" class="btn btn-success">
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
                                <button type="button" onclick="cargarProductosCantidad()" class="btn btn-primary btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="exportarPDF('productos-cantidad')" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="button" onclick="exportarExcel('productos-cantidad')" class="btn btn-outline-success btn-sm">
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
                                <button type="button" onclick="cargarProductosMonto()" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="exportarPDF('productos-monto')" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
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
                                <button type="button" onclick="cargarDistribucionAlmacenes()" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="exportarPDF('almacenes')" class="btn btn-outline-secondary btn-sm">
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
                                <button type="button" onclick="cargarEmpleadosTop()" class="btn btn-danger btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="exportarPDF('empleados')" class="btn btn-outline-danger btn-sm">
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
                                <button type="button" onclick="cargarProductosVencimiento()" class="btn btn-info btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                                <button type="button" onclick="exportarPDF('vencimientos')" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
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
                                    <button class="btn btn-primary" type="button" onclick="cargarProductosVencimiento()">
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
                            <button type="button" class="btn btn-primary" onclick="imprimirReporte()">
                                <i class="bi bi-printer"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let chartProductosCantidad = null;
        let chartProductosMonto = null;
        let chartDistribucionAlmacenes = null;
        let chartEmpleadosTop = null;
        let reporteCompletoModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            reporteCompletoModal = new bootstrap.Modal(document.getElementById('reporteCompletoModal'));
            
            // Función para manejar cambios de pestaña
            function handleTabChange(targetId) {
                switch(targetId) {
                    case '#productos-cantidad':
                        if (!chartProductosCantidad) cargarProductosCantidad();
                        break;
                    case '#productos-monto':
                        if (!chartProductosMonto) cargarProductosMonto();
                        break;
                    case '#almacenes':
                        if (!chartDistribucionAlmacenes) cargarDistribucionAlmacenes();
                        break;
                    case '#empleados':
                        if (!chartEmpleadosTop) cargarEmpleadosTop();
                        break;
                    case '#vencimientos':
                        cargarProductosVencimiento();
                        break;
                }
            }
            
            // Cargar el primer reporte
            cargarProductosCantidad();
            
            // Configurar eventos para las pestañas
            const tabs = document.querySelectorAll('#reportTabs button[data-bs-toggle="tab"]');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    const targetId = event.target.getAttribute('data-bs-target');
                    handleTabChange(targetId);
                });
            });
            
            // Verificar si la pestaña de vencimientos está activa al cargar
            const activeTab = document.querySelector('#reportTabs .nav-link.active');
            if (activeTab) {
                const targetId = activeTab.getAttribute('data-bs-target');
                handleTabChange(targetId);
            }
        });

        function getFiltros() {
            return {
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin: document.getElementById('fecha_fin').value
            };
        }

        // Cargar el reporte activo
        function cargarReporteActivo() {
            const activeTab = document.querySelector('#reportTabs .nav-link.active');
            const targetId = activeTab.getAttribute('data-bs-target');
            
            if (targetId === '#productos-cantidad') {
                cargarProductosCantidad();
            } else if (targetId === '#productos-monto') {
                cargarProductosMonto();
            } else if (targetId === '#almacenes') {
                cargarDistribucionAlmacenes();
            } else if (targetId === '#empleados') {
                cargarEmpleadosTop();
            } else if (targetId === '#vencimientos') {
                cargarProductosVencimiento();
            }
        }

        // 1. Productos más vendidos por cantidad
        function cargarProductosCantidad() {
            const filtros = getFiltros();
            const container = document.getElementById('tablaProductosCantidadContainer');
            container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando...</p></div>';
            
            fetch(`/api/reportes/productos-mas-vendidos-cantidad?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        mostrarTablaProductosCantidad(data.productos, data);
                        crearGraficoProductosCantidad(data.productos);
                    } else {
                        throw new Error(data.message || 'Error en los datos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Error al cargar los datos: ${error.message}
                            <button onclick="cargarProductosCantidad()" class="btn btn-sm btn-outline-danger ms-2">
                                Reintentar
                            </button>
                        </div>`;
                });
        }

        function mostrarTablaProductosCantidad(productos, data) {
            let html = `
                <h6 class="text-muted mb-3">Período: ${document.getElementById('fecha_inicio').value} al ${document.getElementById('fecha_fin').value}</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            if (!productos || productos.length === 0) {
                html = '<div class="alert alert-info">No hay datos para el período seleccionado</div>';
            } else {
                productos.forEach((producto, index) => {
                    html += `
                        <tr>
                            <td><span class="badge bg-primary">${index + 1}</span></td>
                            <td>${producto.nombrePr}</td>
                            <td><strong>${producto.total_cantidad}</strong> unidades</td>
                            <td class="text-success">$${parseFloat(producto.total_monto).toFixed(2)}</td>
                        </tr>`;
                });
                
                html += `</tbody></table>`;
                
                if (data.pagination) {
                    html += `<div class="text-muted text-center mt-2">
                                Mostrando ${productos.length} de ${data.pagination.total} productos
                            </div>`;
                }
            }
            
            document.getElementById('tablaProductosCantidadContainer').innerHTML = html;
        }

        function crearGraficoProductosCantidad(productos) {
            const ctx = document.getElementById('chartProductosCantidad').getContext('2d');
            
            if (chartProductosCantidad) {
                chartProductosCantidad.destroy();
            }
            
            const top10 = productos.slice(0, 10);
            
            if (!top10 || top10.length === 0) {
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.font = '16px Arial';
                ctx.fillStyle = '#999';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            chartProductosCantidad = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: top10.map(p => p.nombrePr.length > 20 ? p.nombrePr.substring(0, 20) + '...' : p.nombrePr),
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: top10.map(p => p.total_cantidad),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad Vendida'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top 10 Productos Más Vendidos (Cantidad)'
                        }
                    }
                }
            });
        }

        // 2. Productos más vendidos por monto
        function cargarProductosMonto() {
            const filtros = getFiltros();
            const container = document.getElementById('tablaProductosMontoContainer');
            container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-warning" role="status"></div><p class="mt-2">Cargando...</p></div>';
            
            fetch(`/api/reportes/productos-mas-vendidos-monto?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTablaProductosMonto(data.productos);
                        crearGraficoProductosMonto(data.productos);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
                });
        }

        function mostrarTablaProductosMonto(productos) {
            let html = `
                <h6 class="text-muted mb-3">Período: ${document.getElementById('fecha_inicio').value} al ${document.getElementById('fecha_fin').value}</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Monto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            if (!productos || productos.length === 0) {
                html = '<div class="alert alert-info">No hay datos para el período seleccionado</div>';
            } else {
                productos.forEach((producto, index) => {
                    html += `
                        <tr>
                            <td><span class="badge bg-warning text-dark">${index + 1}</span></td>
                            <td>${producto.nombrePr}</td>
                            <td class="text-success"><strong>$${parseFloat(producto.total_monto).toFixed(2)}</strong></td>
                            <td>${producto.total_cantidad} unidades</td>
                        </tr>`;
                });
                
                html += `</tbody></table>`;
            }
            
            document.getElementById('tablaProductosMontoContainer').innerHTML = html;
        }

        function crearGraficoProductosMonto(productos) {
            const ctx = document.getElementById('chartProductosMonto').getContext('2d');
            
            if (chartProductosMonto) {
                chartProductosMonto.destroy();
            }
            
            const top10 = productos.slice(0, 10);
            
            if (!top10 || top10.length === 0) {
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.font = '16px Arial';
                ctx.fillStyle = '#999';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            chartProductosMonto = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: top10.map(p => p.nombrePr.length > 20 ? p.nombrePr.substring(0, 20) + '...' : p.nombrePr),
                    datasets: [{
                        label: 'Monto Total ($)',
                        data: top10.map(p => p.total_monto),
                        backgroundColor: 'rgba(255, 206, 86, 0.5)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Monto ($)'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top 10 Productos Más Vendidos (Monto)'
                        }
                    }
                }
            });
        }

        // 3. Distribución en almacenes
        function cargarDistribucionAlmacenes() {
            const container = document.getElementById('tablaDistribucionAlmacenesContainer');
            container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-secondary" role="status"></div><p class="mt-2">Cargando...</p></div>';
            
            fetch('/api/reportes/distribucion-almacenes')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTablaDistribucionAlmacenes(data.distribucion, data.total_stock);
                        crearGraficoDistribucionAlmacenes(data.distribucion);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
                });
        }

        function mostrarTablaDistribucionAlmacenes(distribucion, totalStock) {
            let html = `
                <h6 class="text-muted mb-3">Stock Total: <strong>${totalStock}</strong> unidades</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Almacén</th>
                                <th>Stock</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            if (!distribucion || distribucion.length === 0) {
                html = '<div class="alert alert-info">No hay datos de almacenes</div>';
            } else {
                distribucion.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.nombreAl}</td>
                            <td><span class="badge bg-secondary">${item.total_stock}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: ${item.porcentaje}%;">
                                        </div>
                                    </div>
                                    <small>${item.porcentaje}%</small>
                                </div>
                            </td>
                        </tr>`;
                });
                
                html += `</tbody></table>`;
            }
            
            document.getElementById('tablaDistribucionAlmacenesContainer').innerHTML = html;
        }

        function crearGraficoDistribucionAlmacenes(distribucion) {
            const ctx = document.getElementById('chartDistribucionAlmacenes').getContext('2d');
            
            if (chartDistribucionAlmacenes) {
                chartDistribucionAlmacenes.destroy();
            }
            
            if (!distribucion || distribucion.length === 0) {
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.font = '16px Arial';
                ctx.fillStyle = '#999';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            const colors = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#8AC926', '#1982C4',
                '#6A4C93', '#FF595E'
            ];
            
            chartDistribucionAlmacenes = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: distribucion.map(d => d.nombreAl),
                    datasets: [{
                        data: distribucion.map(d => d.total_stock),
                        backgroundColor: colors.slice(0, distribucion.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Distribución de Stock por Almacén'
                        },
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // 4. Empleados top ventas
        function cargarEmpleadosTop() {
            const filtros = getFiltros();
            const container = document.getElementById('tablaEmpleadosTopContainer');
            container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-danger" role="status"></div><p class="mt-2">Cargando...</p></div>';
            
            fetch(`/api/reportes/empleados-top-ventas?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTablaEmpleadosTop(data.empleados);
                        crearGraficoEmpleadosTop(data.empleados);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
                });
        }

        function mostrarTablaEmpleadosTop(empleados) {
            let html = `
                <h6 class="text-muted mb-3">Período: ${document.getElementById('fecha_inicio').value} al ${document.getElementById('fecha_fin').value}</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Empleado</th>
                                <th>Ventas</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            if (!empleados || empleados.length === 0) {
                html = '<div class="alert alert-info">No hay datos para el período seleccionado</div>';
            } else {
                empleados.forEach((empleado, index) => {
                    html += `
                        <tr>
                            <td><span class="badge bg-danger">${index + 1}</span></td>
                            <td>${empleado.nombreEm} ${empleado.apellidosEm}</td>
                            <td><strong>${empleado.total_ventas}</strong> ventas</td>
                            <td class="text-success">$${parseFloat(empleado.total_monto_ventas).toFixed(2)}</td>
                        </tr>`;
                });
                
                html += `</tbody></table>`;
            }
            
            document.getElementById('tablaEmpleadosTopContainer').innerHTML = html;
        }

        function crearGraficoEmpleadosTop(empleados) {
            const ctx = document.getElementById('chartEmpleadosTop').getContext('2d');
            
            if (chartEmpleadosTop) {
                chartEmpleadosTop.destroy();
            }
            
            const top5 = empleados.slice(0, 5);
            
            if (!top5 || top5.length === 0) {
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.font = '16px Arial';
                ctx.fillStyle = '#999';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }
            
            chartEmpleadosTop = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: top5.map(e => `${e.nombreEm.substring(0, 1)}. ${e.apellidosEm.split(' ')[0]}`),
                    datasets: [
                        {
                            label: 'Número de Ventas',
                            data: top5.map(e => e.total_ventas),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Monto Total ($)',
                            data: top5.map(e => e.total_monto_ventas),
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Número de Ventas'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Monto ($)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top 5 Empleados por Ventas'
                        }
                    }
                }
            });
        }

        // 5. Productos por vencer
        function cargarProductosVencimiento() {
            const diasLimite = document.getElementById('diasLimite').value;
            
            // Mostrar loading
            document.getElementById('listaProximos').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                    <p class="mt-2 text-muted">Cargando productos próximos a vencer...</p>
                </div>`;
            document.getElementById('listaVencidos').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                    <p class="mt-2 text-muted">Cargando productos vencidos...</p>
                </div>`;
            document.getElementById('contadorVencimientos').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                    <p class="mt-2 text-muted">Cargando estado...</p>
                </div>`;
            
            // Ocultar mensaje de todo bien
            document.getElementById('mensajeTodoBien').classList.add('d-none');
            
            fetch(`/api/reportes/productos-vencimiento?dias_limite=${diasLimite}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        mostrarContadorVencimientos(data);
                        mostrarListaProximos(data.productos_proximos, data);
                        mostrarListaVencidos(data.productos_vencidos, data);
                        
                        // Mostrar mensaje si no hay productos
                        if (data.total_proximos === 0 && data.total_vencidos === 0) {
                            document.getElementById('mensajeTodoBien').classList.remove('d-none');
                        }
                    } else {
                        throw new Error(data.message || 'Error en los datos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('listaProximos').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Error al cargar los datos: ${error.message}
                            <button onclick="cargarProductosVencimiento()" class="btn btn-sm btn-outline-danger ms-2">
                                Reintentar
                            </button>
                        </div>`;
                    document.getElementById('listaVencidos').innerHTML = '';
                });
        }

        function mostrarContadorVencimientos(data) {
            const html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-warning border-2">
                            <div class="card-body text-center">
                                <h6 class="card-title text-warning">
                                    <i class="bi bi-clock"></i> Próximos a Vencer
                                </h6>
                                <h2 class="mb-0 ${data.total_proximos > 0 ? 'text-warning' : 'text-success'}">
                                    ${data.total_proximos}
                                </h2>
                                <small class="text-muted">en ${data.dias_limite} días</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card ${data.total_vencidos > 0 ? 'border-danger border-2' : 'border-success border-2'}">
                            <div class="card-body text-center">
                                <h6 class="card-title ${data.total_vencidos > 0 ? 'text-danger' : 'text-success'}">
                                    <i class="bi ${data.total_vencidos > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle'}"></i> 
                                    Vencidos
                                </h6>
                                <h2 class="mb-0 ${data.total_vencidos > 0 ? 'text-danger' : 'text-success'}">
                                    ${data.total_vencidos}
                                </h2>
                                <small class="text-muted">últimos 90 días</small>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            document.getElementById('contadorVencimientos').innerHTML = html;
            document.getElementById('contadorProximos').textContent = data.total_proximos;
            document.getElementById('contadorVencidos').textContent = data.total_vencidos;
        }

        function mostrarListaProximos(productos, data) {
            let html = '';
            
            if (!productos || productos.length === 0) {
                html = `
                    <div class="alert alert-success border-0">
                        <i class="bi bi-check-circle"></i>
                        <strong>¡Buen trabajo!</strong> No hay productos próximos a vencer.
                        <small class="d-block mt-1">Período analizado: próximos ${data.dias_limite} días</small>
                    </div>`;
            } else {
                productos.forEach((producto, index) => {
                    // Determinar color según días restantes
                    let colorBadge = 'bg-success';
                    let icono = 'bi-clock';
                    
                    if (producto.dias_restantes <= 7) {
                        colorBadge = 'bg-danger';
                        icono = 'bi-exclamation-triangle';
                    } else if (producto.dias_restantes <= 15) {
                        colorBadge = 'bg-warning';
                        icono = 'bi-exclamation-circle';
                    }
                    
                    html += `
                        <div class="list-group-item border-0 px-0 ${index > 0 ? 'pt-3' : ''}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${producto.nombrePr}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> ${producto.categoria?.nombreCa || 'Sin categoría'}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge ${colorBadge}">
                                        <i class="bi ${icono}"></i> ${producto.dias_restantes} días
                                    </span>
                                    <div class="text-muted small mt-1">
                                        ${new Date(producto.fechaVencimiento).toLocaleDateString()}
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
                
                // Agregar footer informativo
                html += `
                    <div class="list-group-item border-0 px-0 pt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Mostrando ${productos.length} producto(s) que vence(n) en los próximos ${data.dias_limite} días
                        </small>
                    </div>`;
            }
            
            document.getElementById('listaProximos').innerHTML = html;
        }

        function mostrarListaVencidos(productos, data) {
            let html = '';
            
            if (!productos || productos.length === 0) {
                html = `
                    <div class="alert alert-success border-0">
                        <i class="bi bi-check-circle"></i>
                        <strong>¡Perfecto!</strong> No hay productos vencidos.
                        <small class="d-block mt-1">Período analizado: últimos 90 días</small>
                    </div>`;
            } else {
                productos.forEach((producto, index) => {
                    html += `
                        <div class="list-group-item border-0 px-0 ${index > 0 ? 'pt-3' : ''}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${producto.nombrePr}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> ${producto.categoria?.nombreCa || 'Sin categoría'}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-dark">
                                        <i class="bi bi-clock-history"></i> ${producto.dias_vencido} días
                                    </span>
                                    <div class="text-danger small mt-1">
                                        ${new Date(producto.fechaVencimiento).toLocaleDateString()}
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
                
                // Agregar footer informativo
                html += `
                    <div class="list-group-item border-0 px-0 pt-3">
                        <div class="alert alert-danger border-0 py-2">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Atención:</strong> ${productos.length} producto(s) requieren revisión inmediata
                        </div>
                    </div>`;
            }
            
            document.getElementById('listaVencidos').innerHTML = html;
        }

        // 6. Reporte completo en modal
        function generarReporteCompleto() {
            const filtros = getFiltros();
            const content = document.getElementById('reporteCompletoContent');
            
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><p class="mt-2">Generando reporte...</p></div>';
            
            // Mostrar el modal
            reporteCompletoModal.show();
            
            fetch(`/api/reportes/completo?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarReporteCompleto(data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<div class="alert alert-danger">Error al generar el reporte</div>';
                });
        }

        function mostrarReporteCompleto(data) {
            let html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-trophy"></i> Producto Top en Cantidad</h6>
                            </div>
                            <div class="card-body">
                                ${data.productos_mas_vendidos_cantidad && data.productos_mas_vendidos_cantidad.length > 0 ? `
                                    <h5 class="text-primary">${data.productos_mas_vendidos_cantidad[0].nombrePr}</h5>
                                    <p><strong>Cantidad:</strong> ${data.productos_mas_vendidos_cantidad[0].total_cantidad} unidades</p>
                                    <p><strong>Monto:</strong> $${parseFloat(data.productos_mas_vendidos_cantidad[0].total_monto).toFixed(2)}</p>
                                ` : '<p class="text-muted"><em>No hay datos</em></p>'}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0"><i class="bi bi-currency-dollar"></i> Producto Top en Monto</h6>
                            </div>
                            <div class="card-body">
                                ${data.productos_mas_vendidos_monto && data.productos_mas_vendidos_monto.length > 0 ? `
                                    <h5 class="text-warning">${data.productos_mas_vendidos_monto[0].nombrePr}</h5>
                                    <p><strong>Monto:</strong> $${parseFloat(data.productos_mas_vendidos_monto[0].total_monto).toFixed(2)}</p>
                                    <p><strong>Cantidad:</strong> ${data.productos_mas_vendidos_monto[0].total_cantidad} unidades</p>
                                ` : '<p class="text-muted"><em>No hay datos</em></p>'}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="bi bi-person-badge"></i> Empleado Top en Ventas</h6>
                            </div>
                            <div class="card-body">
                                ${data.empleados_top && data.empleados_top.length > 0 ? `
                                    <h5 class="text-danger">${data.empleados_top[0].nombreEm} ${data.empleados_top[0].apellidosEm}</h5>
                                    <p><strong>Ventas:</strong> ${data.empleados_top[0].total_ventas} transacciones</p>
                                    <p><strong>Monto Generado:</strong> $${parseFloat(data.empleados_top[0].total_monto_ventas).toFixed(2)}</p>
                                ` : '<p class="text-muted"><em>No hay datos</em></p>'}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-secondary h-100">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="bi bi-building"></i> Almacén Principal</h6>
                            </div>
                            <div class="card-body">
                                ${data.distribucion_almacenes && data.distribucion_almacenes.length > 0 ? `
                                    <h5 class="text-secondary">${data.distribucion_almacenes[0].nombreAl}</h5>
                                    <p><strong>Stock:</strong> ${data.distribucion_almacenes[0].total_stock} unidades</p>
                                    <p><strong>Porcentaje:</strong> ${data.distribucion_almacenes[0].porcentaje}% del total</p>
                                ` : '<p class="text-muted"><em>No hay datos</em></p>'}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle text-info"></i> Información del Período</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha Inicio:</strong> ${data.periodo.fecha_inicio}</p>
                                <p><strong>Fecha Fin:</strong> ${data.periodo.fecha_fin}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Generado:</strong> ${new Date().toLocaleString()}</p>
                                <p><strong>Total de Datos:</strong> ${(data.productos_mas_vendidos_cantidad ? data.productos_mas_vendidos_cantidad.length : 0) + (data.productos_mas_vendidos_monto ? data.productos_mas_vendidos_monto.length : 0) + (data.distribucion_almacenes ? data.distribucion_almacenes.length : 0) + (data.empleados_top ? data.empleados_top.length : 0)} registros</p>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            document.getElementById('reporteCompletoContent').innerHTML = html;
        }
        
        function imprimirReporte() {
            window.print();
        }

        // 7. Funciones de exportación
        function exportarPDF(tipo) {
            const filtros = getFiltros();
            let url = `/reportes/exportar/pdf/${tipo}?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`;
            
            // Para vencimientos, añadir parámetros adicionales
            if (tipo === 'vencimientos') {
                const diasLimite = document.getElementById('diasLimite').value;
                url += `&dias_limite=${diasLimite}`;
            }
            
            window.open(url, '_blank');
        }

        function exportarExcel(tipo) {
            const filtros = getFiltros();
            const url = `/reportes/exportar/excel/${tipo}?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`;
            window.open(url, '_blank');
        }
    </script>
    @endsection 