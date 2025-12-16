// reports.js - Funcionalidad para reportes

let chartProductosCantidad = null;
let chartProductosMonto = null;
let chartDistribucionAlmacenes = null;
let chartEmpleadosTop = null;
let reporteCompletoModal = null;

// Variables globales para acceso desde funciones
window.reportes = {
    // Función principal de inicialización
    init: function() {
        reporteCompletoModal = new bootstrap.Modal(document.getElementById('reporteCompletoModal'));
        
        // Configurar eventos para las pestañas
        const tabs = document.querySelectorAll('#reportTabs button[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const targetId = event.target.getAttribute('data-bs-target');
                window.reportes.handleTabChange(targetId);
            });
        });
        
        // Cargar el primer reporte
        window.reportes.cargarProductosCantidad();
        
        // Verificar si la pestaña de vencimientos está activa al cargar
        const activeTab = document.querySelector('#reportTabs .nav-link.active');
        if (activeTab) {
            const targetId = activeTab.getAttribute('data-bs-target');
            window.reportes.handleTabChange(targetId);
        }
    },

    // Manejar cambio de pestaña
    handleTabChange: function(targetId) {
        switch(targetId) {
            case '#productos-cantidad':
                if (!chartProductosCantidad) window.reportes.cargarProductosCantidad();
                break;
            case '#productos-monto':
                if (!chartProductosMonto) window.reportes.cargarProductosMonto();
                break;
            case '#almacenes':
                if (!chartDistribucionAlmacenes) window.reportes.cargarDistribucionAlmacenes();
                break;
            case '#empleados':
                if (!chartEmpleadosTop) window.reportes.cargarEmpleadosTop();
                break;
            case '#vencimientos':
                window.reportes.cargarProductosVencimiento();
                break;
        }
    },

    // Obtener filtros actuales
    getFiltros: function() {
        return {
            fecha_inicio: document.getElementById('fecha_inicio').value,
            fecha_fin: document.getElementById('fecha_fin').value
        };
    },

    // Cargar el reporte activo
    cargarReporteActivo: function() {
        const activeTab = document.querySelector('#reportTabs .nav-link.active');
        const targetId = activeTab.getAttribute('data-bs-target');
        
        if (targetId === '#productos-cantidad') {
            window.reportes.cargarProductosCantidad();
        } else if (targetId === '#productos-monto') {
            window.reportes.cargarProductosMonto();
        } else if (targetId === '#almacenes') {
            window.reportes.cargarDistribucionAlmacenes();
        } else if (targetId === '#empleados') {
            window.reportes.cargarEmpleadosTop();
        } else if (targetId === '#vencimientos') {
            window.reportes.cargarProductosVencimiento();
        }
    },

    // 1. Productos más vendidos por cantidad
    cargarProductosCantidad: function() {
        const filtros = window.reportes.getFiltros();
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
                    window.reportes.mostrarTablaProductosCantidad(data.productos, data);
                    window.reportes.crearGraficoProductosCantidad(data.productos);
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
                        <button onclick="reportes.cargarProductosCantidad()" class="btn btn-sm btn-outline-danger ms-2">
                            Reintentar
                        </button>
                    </div>`;
            });
    },

    mostrarTablaProductosCantidad: function(productos, data) {
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
    },

    crearGraficoProductosCantidad: function(productos) {
        const ctx = document.getElementById('chartProductosCantidad').getContext('2d');
        
        if (chartProductosCantidad) {
            chartProductosCantidad.destroy();
        }
        
        const top10 = productos.slice(0, 10);
        
        if (!top10 || top10.length === 0) {
            window.reportes.mostrarMensajeSinDatos(ctx);
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
    },

    // 2. Productos más vendidos por monto
    cargarProductosMonto: function() {
        const filtros = window.reportes.getFiltros();
        const container = document.getElementById('tablaProductosMontoContainer');
        container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-warning" role="status"></div><p class="mt-2">Cargando...</p></div>';
        
        fetch(`/api/reportes/productos-mas-vendidos-monto?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.reportes.mostrarTablaProductosMonto(data.productos);
                    window.reportes.crearGraficoProductosMonto(data.productos);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
            });
    },

    mostrarTablaProductosMonto: function(productos) {
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
    },

    crearGraficoProductosMonto: function(productos) {
        const ctx = document.getElementById('chartProductosMonto').getContext('2d');
        
        if (chartProductosMonto) {
            chartProductosMonto.destroy();
        }
        
        const top10 = productos.slice(0, 10);
        
        if (!top10 || top10.length === 0) {
            window.reportes.mostrarMensajeSinDatos(ctx);
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
    },

    // 3. Distribución en almacenes
    cargarDistribucionAlmacenes: function() {
        const container = document.getElementById('tablaDistribucionAlmacenesContainer');
        container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-secondary" role="status"></div><p class="mt-2">Cargando...</p></div>';
        
        fetch('/api/reportes/distribucion-almacenes')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.reportes.mostrarTablaDistribucionAlmacenes(data.distribucion, data.total_stock);
                    window.reportes.crearGraficoDistribucionAlmacenes(data.distribucion);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
            });
    },

    mostrarTablaDistribucionAlmacenes: function(distribucion, totalStock) {
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
    },

    crearGraficoDistribucionAlmacenes: function(distribucion) {
        const ctx = document.getElementById('chartDistribucionAlmacenes').getContext('2d');
        
        if (chartDistribucionAlmacenes) {
            chartDistribucionAlmacenes.destroy();
        }
        
        if (!distribucion || distribucion.length === 0) {
            window.reportes.mostrarMensajeSinDatos(ctx);
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
    },

    // 4. Empleados top ventas
    cargarEmpleadosTop: function() {
        const filtros = window.reportes.getFiltros();
        const container = document.getElementById('tablaEmpleadosTopContainer');
        container.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-danger" role="status"></div><p class="mt-2">Cargando...</p></div>';
        
        fetch(`/api/reportes/empleados-top-ventas?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.reportes.mostrarTablaEmpleadosTop(data.empleados);
                    window.reportes.crearGraficoEmpleadosTop(data.empleados);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>';
            });
    },

    mostrarTablaEmpleadosTop: function(empleados) {
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
    },

    crearGraficoEmpleadosTop: function(empleados) {
        const ctx = document.getElementById('chartEmpleadosTop').getContext('2d');
        
        if (chartEmpleadosTop) {
            chartEmpleadosTop.destroy();
        }
        
        const top5 = empleados.slice(0, 5);
        
        if (!top5 || top5.length === 0) {
            window.reportes.mostrarMensajeSinDatos(ctx);
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
    },

    // 5. Productos por vencer
    cargarProductosVencimiento: function() {
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
                    window.reportes.mostrarContadorVencimientos(data);
                    window.reportes.mostrarListaProximos(data.productos_proximos, data);
                    window.reportes.mostrarListaVencidos(data.productos_vencidos, data);
                    
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
                        <button onclick="reportes.cargarProductosVencimiento()" class="btn btn-sm btn-outline-danger ms-2">
                            Reintentar
                        </button>
                    </div>`;
                document.getElementById('listaVencidos').innerHTML = '';
            });
    },

    mostrarContadorVencimientos: function(data) {
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
    },

    mostrarListaProximos: function(productos, data) {
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
            
            // Manejar categoría nula
            const categoriaNombre = producto.categoria?.nombreCa || 
                                   producto.categoria || 
                                   'Sin categoría';
            
            html += `
                <div class="list-group-item border-0 px-0 ${index > 0 ? 'pt-3' : ''}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${producto.nombrePr}</h6>
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> ${categoriaNombre}
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
},

    mostrarListaVencidos: function(productos, data) {
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
            // Manejar categoría nula
            const categoriaNombre = producto.categoria?.nombreCa || 
                                   producto.categoria || 
                                   'Sin categoría';
            
            html += `
                <div class="list-group-item border-0 px-0 ${index > 0 ? 'pt-3' : ''}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${producto.nombrePr}</h6>
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> ${categoriaNombre}
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
},

    // 6. Reporte completo en modal
    generarReporteCompleto: function() {
        const filtros = window.reportes.getFiltros();
        const content = document.getElementById('reporteCompletoContent');
        
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><p class="mt-2">Generando reporte...</p></div>';
        
        // Mostrar el modal
        reporteCompletoModal.show();
        
        fetch(`/api/reportes/completo?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.reportes.mostrarReporteCompleto(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<div class="alert alert-danger">Error al generar el reporte</div>';
            });
    },

    mostrarReporteCompleto: function(data) {
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
    },
    
    imprimirReporte: function() {
        window.print();
    },

    // 7. Funciones de exportación
exportarPDF: function(tipo) {
    const filtros = window.reportes.getFiltros();
    let url = `/reportes/exportar/pdf/${tipo}?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`;
    
    // Para vencimientos, añadir parámetros adicionales
    if (tipo === 'vencimientos') {
        const diasLimite = document.getElementById('diasLimite').value;
        url += `&dias_limite=${diasLimite}`;
    }
    
    console.log('📤 URL de exportación:', url);
    window.open(url, '_blank');
},

    exportarExcel: function(tipo) {
        const filtros = window.reportes.getFiltros();
        const url = `/reportes/exportar/excel/${tipo}?fecha_inicio=${filtros.fecha_inicio}&fecha_fin=${filtros.fecha_fin}`;
        window.open(url, '_blank');
    },

    // Función auxiliar para mostrar mensaje de no datos
    mostrarMensajeSinDatos: function(ctx) {
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.font = '16px Arial';
        ctx.fillStyle = '#999';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos disponibles', ctx.canvas.width / 2, ctx.canvas.height / 2);
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.reportes.init();
});