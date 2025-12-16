@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/rutas.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    /* Estilos para autocompletado */
    .list-group {
        position: absolute;
        z-index: 1000;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .list-group-item {
        cursor: pointer;
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .position-relative {
        position: relative;
    }
    .badge-existente {
        background-color: #0dcaf0 !important;
    }
    .badge-nuevo {
        background-color: #6c757d !important;
    }
</style>

<h1 class="text-center mb-4">Módulo de Usuarios</h1>

<!-- Mensajes de sesión -->
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    @if(session('info'))
    <hr>
    <p class="mb-0"><small>{{ session('info') }}</small></p>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-octagon-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<section class="content">
    <div class="center-wrapper">
        <section class="content">

            <div class="card shadow-sm p-4 mb-4">
                <form action="/mUser/guardar" method="POST" class="row g-3" id="formUsuario">
                    @csrf

                    <!-- ======================= -->
                    <!--   SECCIÓN: EMPLEADO    -->
                    <!-- ======================= -->
                    <h4 class="mt-4">Datos del Empleado
                        <span class="badge badge-nuevo float-end" id="empleadoStatus">Nuevo</span>
                    </h4>
                    <hr>

                    <!-- Nombre empleado -->
                    <div class="col-md-6 position-relative">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="nombreEm" name="nombreEm" class="form-control" 
                               placeholder="Ingrese el nombre del empleado" autocomplete="off" required>
                        <div id="suggestionsEmpleado" class="list-group position-absolute w-100" style="display:none;"></div>
                        <small class="text-muted">Escriba para buscar empleados existentes</small>
                    </div>

                    <!-- Apellidos -->
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" id="apellidosEm" name="apellidosEm" class="form-control" 
                               placeholder="Ingrese los apellidos" required>
                    </div>

                    <!-- Sueldo -->
                    <div class="col-md-6">
                        <label class="form-label">Sueldo</label>
                        <input type="text" id="sueldoEm" name="sueldoEm" class="form-control" 
                               placeholder="Ingrese sueldo" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="telefonoEm" name="telefonoEm" class="form-control" 
                               placeholder="Ingrese teléfono" required>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" 
                               placeholder="Ingrese dirección" required>
                    </div>

                    <!-- ==================================== -->
                    <!--   SECCIÓN: TIPO DE EMPLEADO         -->
                    <!-- ==================================== -->
                    <h4 class="mt-4">Tipo de Empleado
                        <span class="badge badge-nuevo float-end" id="tipoStatus">Nuevo</span>
                    </h4>
                    <hr>

                    <div class="col-md-6 position-relative">
                        <label class="form-label">Tipo de Empleado</label>
                        <input type="text" id="nombreE" name="nombreE" class="form-control" 
                               placeholder="Ej: Cajero, Gerente, Repartidor" autocomplete="off" required>
                        <div id="suggestionsTipo" class="list-group position-absolute w-100" style="display:none;"></div>
                        <small class="text-muted">Escriba para buscar tipos existentes</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" id="descripcionTip" name="descripcionTip" class="form-control" 
                               placeholder="Descripción de que hace el empleado" required>
                    </div>

                    <!-- ==================================== -->
                    <!--   SECCIÓN: RUTAS DE ACCESO         -->
                    <!-- ==================================== -->
                    <div class="col-12">
                        <div class="mb-3 rutas-dropdown-container">
                            <label class="form-label fw-bold">Selecciona Rutas de Acceso:</label>
                        
                            <!-- Dropdown para seleccionar rutas -->
                            <div class="dropdown mb-2">
                                <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center" 
                                        type="button" 
                                        id="dropdownRutas" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <span id="rutasButtonText">Elige rutas</span>
                                    <span class="dropdown-toggle"></span>
                                </button>
                                
                                <ul class="dropdown-menu dropdown-menu-rutas" aria-labelledby="dropdownRutas" style="width: 100%; max-height: 300px; overflow-y: auto;">
                                    <!-- Rutas fijas COMPLETAS -->
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
                            <input type="hidden" name="rutas_iniciales" id="rutasInputHidden" value="">
                            
                            <!-- Pequeña ayuda -->
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle"></i> Selecciona las rutas que tendrá acceso este tipo de empleado
                            </div>
                        </div>
                    </div>

                    <!-- ======================= -->
                    <!--   SECCIÓN: USUARIO     -->
                    <!-- ======================= -->
                    <h4 class="mt-3">Datos del Usuario
                        <span class="badge badge-nuevo float-end" id="usuarioStatus">Nuevo</span>
                    </h4>
                    <hr>

                    <!-- Nombre -->
                    <div class="col-md-4 position-relative">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               placeholder="Ingrese nombre del usuario" autocomplete="off" required>
                        <div id="suggestionsUsuario" class="list-group position-absolute w-100" style="display:none;"></div>
                        <small class="text-muted">Escriba para buscar usuarios existentes</small>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-4">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="usuario@dominio.com" required>
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               minlength="8" placeholder="Mínimo 8 caracteres" required>
                        <small class="text-muted">Para usuarios existentes, esta será la nueva contraseña</small>
                    </div>


                    <!-- BOTÓN FINAL -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-2"></i> Guardar Todo
                        </button>
                        <button type="button" id="btnLimpiar" class="btn btn-outline-secondary btn-lg ms-2">
                            <i class="bi bi-arrow-clockwise me-2"></i> Limpiar
                        </button>
                    </div>
                </form>
            </div>

        </section>
    </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/rutas.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Variables de estado
    let empleadoCargado = false;
    let tipoCargado = false;
    let usuarioCargado = false;
    let rutasCargadas = '';
    
    // Elementos del DOM
    const nombreEmInput = document.getElementById('nombreEm');
    const nombreEInput = document.getElementById('nombreE');
    const nameInput = document.getElementById('name');
    
    const suggestionsEmpleado = document.getElementById('suggestionsEmpleado');
    const suggestionsTipo = document.getElementById('suggestionsTipo');
    const suggestionsUsuario = document.getElementById('suggestionsUsuario');

    // ====================================
    // 1. AUTOCOMPLETADO PARA EMPLEADOS
    // ====================================
    nombreEmInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsEmpleado.style.display = 'none';
            return;
        }

        fetch(`/mUser/buscar-empleados?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsEmpleado.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(empleado => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${empleado.nombreEm} ${empleado.apellidosEm}`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Cargar datos del empleado
                            nombreEmInput.value = empleado.nombreEm;
                            document.getElementById('apellidosEm').value = empleado.apellidosEm || '';
                            document.getElementById('sueldoEm').value = empleado.sueldoEm || '';
                            document.getElementById('telefonoEm').value = empleado.telefonoEm || '';
                            document.getElementById('direccion').value = empleado.direccion || '';
                            
                            // Cargar tipo de empleado si existe
                            if (empleado.tipoEmpleado) {
                                document.getElementById('nombreE').value = empleado.tipoEmpleado.nombreE || '';
                                document.getElementById('descripcionTip').value = empleado.tipoEmpleado.descripcionTip || '';
                                tipoCargado = true;
                                document.getElementById('tipoStatus').textContent = 'Existente';
                                document.getElementById('tipoStatus').className = 'badge badge-existente float-end';
                                
                                // Cargar rutas del tipo
                                cargarRutasDesdeTipo(empleado.tipoEmpleado.rutas_acceso);
                            }
                            
                            // Cargar usuario si existe
                            if (empleado.user) {
                                document.getElementById('name').value = empleado.user.name || '';
                                document.getElementById('email').value = empleado.user.email || '';
                                usuarioCargado = true;
                                document.getElementById('usuarioStatus').textContent = 'Existente';
                                document.getElementById('usuarioStatus').className = 'badge badge-existente float-end';
                            }
                            
                            empleadoCargado = true;
                            document.getElementById('empleadoStatus').textContent = 'Existente';
                            document.getElementById('empleadoStatus').className = 'badge badge-existente float-end';
                            updateResumen();
                            
                            suggestionsEmpleado.style.display = 'none';
                            showMessage('success', 'Empleado cargado exitosamente');
                        });
                        
                        suggestionsEmpleado.appendChild(item);
                    });
                    
                    suggestionsEmpleado.style.display = 'block';
                } else {
                    suggestionsEmpleado.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar empleados:', error);
                suggestionsEmpleado.style.display = 'none';
            });
    });

    // ====================================
    // 2. AUTOCOMPLETADO PARA TIPOS DE EMPLEADO
    // ====================================
    nombreEInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsTipo.style.display = 'none';
            return;
        }

        fetch(`/mUser/buscar-tipos?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsTipo.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(tipo => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${tipo.nombreE} - ${tipo.descripcionTip}`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            nombreEInput.value = tipo.nombreE;
                            document.getElementById('descripcionTip').value = tipo.descripcionTip || '';
                            
                            // Cargar rutas del tipo
                            cargarRutasDesdeTipo(tipo.rutas_acceso);
                            
                            tipoCargado = true;
                            document.getElementById('tipoStatus').textContent = 'Existente';
                            document.getElementById('tipoStatus').className = 'badge badge-existente float-end';
                            updateResumen();
                            
                            suggestionsTipo.style.display = 'none';
                            showMessage('info', 'Tipo de empleado cargado exitosamente');
                        });
                        
                        suggestionsTipo.appendChild(item);
                    });
                    
                    suggestionsTipo.style.display = 'block';
                } else {
                    suggestionsTipo.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar tipos:', error);
                suggestionsTipo.style.display = 'none';
            });
    });

    // ====================================
    // 3. AUTOCOMPLETADO PARA USUARIOS
    // ====================================
    nameInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsUsuario.style.display = 'none';
            return;
        }

        fetch(`/mUser/buscar-usuarios?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsUsuario.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(user => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${user.name} (${user.email})`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            nameInput.value = user.name;
                            document.getElementById('email').value = user.email || '';
                            
                            // Cargar empleado si existe
                            if (user.empleado) {
                                document.getElementById('nombreEm').value = user.empleado.nombreEm || '';
                                document.getElementById('apellidosEm').value = user.empleado.apellidosEm || '';
                                empleadoCargado = true;
                                document.getElementById('empleadoStatus').textContent = 'Existente';
                                document.getElementById('empleadoStatus').className = 'badge badge-existente float-end';
                            }
                            
                            usuarioCargado = true;
                            document.getElementById('usuarioStatus').textContent = 'Existente';
                            document.getElementById('usuarioStatus').className = 'badge badge-existente float-end';
                            updateResumen();
                            
                            suggestionsUsuario.style.display = 'none';
                            showMessage('info', 'Usuario cargado exitosamente');
                        });
                        
                        suggestionsUsuario.appendChild(item);
                    });
                    
                    suggestionsUsuario.style.display = 'block';
                } else {
                    suggestionsUsuario.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar usuarios:', error);
                suggestionsUsuario.style.display = 'none';
            });
    });

    // ====================================
    // 4. FUNCIÓN PARA CARGAR RUTAS DESDE TIPO
    // ====================================
    function cargarRutasDesdeTipo(rutasString) {
        if (!rutasString) return;
        
        // Limpiar todas las selecciones
        document.querySelectorAll('.ruta-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Convertir string de rutas a array (ej: "R,U,C" → ["R", "U", "C"])
        const rutasArray = rutasString.split(',').map(r => r.trim()).filter(r => r);
        
        // Marcar checkboxes correspondientes
        rutasArray.forEach(rutaInicial => {
            const checkbox = document.querySelector(`.ruta-checkbox[value="${rutaInicial}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        
        // Actualizar visualización de rutas
        actualizarRutasSeleccionadas();
        
        rutasCargadas = rutasString;
    }

    // ====================================
    // 5. OCULTAR SUGERENCIAS AL HACER CLIC FUERA
    // ====================================
    document.addEventListener('click', function(e) {
        if (!suggestionsEmpleado.contains(e.target) && e.target !== nombreEmInput) {
            suggestionsEmpleado.style.display = 'none';
        }
        if (!suggestionsTipo.contains(e.target) && e.target !== nombreEInput) {
            suggestionsTipo.style.display = 'none';
        }
        if (!suggestionsUsuario.contains(e.target) && e.target !== nameInput) {
            suggestionsUsuario.style.display = 'none';
        }
    });

    // ====================================
    // 6. FUNCIONES AUXILIARES
    // ====================================
    function updateResumen() {
        document.getElementById('resumenEmpleado').textContent = empleadoCargado ? 'Existente' : 'Nuevo';
        document.getElementById('resumenTipo').textContent = tipoCargado ? 'Existente' : 'Nuevo';
        document.getElementById('resumenUsuario').textContent = usuarioCargado ? 'Existente' : 'Nuevo';
        
        // Actualizar rutas en resumen
        const rutasSeleccionadas = document.querySelectorAll('.ruta-checkbox:checked');
        if (rutasSeleccionadas.length > 0) {
            const nombresRutas = Array.from(rutasSeleccionadas).map(cb => {
                return cb.getAttribute('data-nombre');
            }).join(', ');
            document.getElementById('resumenRutas').textContent = nombresRutas;
        } else {
            document.getElementById('resumenRutas').textContent = 'Ninguna';
        }
    }
    
    function showMessage(type, text) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const icon = type === 'success' ? 'bi-check-circle' : 
                    type === 'error' ? 'bi-exclamation-circle' : 
                    type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle';
        
        const message = document.createElement('div');
        message.className = `alert ${alertClass} alert-dismissible fade show`;
        message.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        message.innerHTML = `
            <i class="bi ${icon} me-2"></i>${text}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(message);
        
        setTimeout(() => {
            if (message.parentNode) {
                message.parentNode.removeChild(message);
            }
        }, 3000);
    }

    // ====================================
    // 7. LIMPIAR FORMULARIO
    // ====================================
    document.getElementById('btnLimpiar').addEventListener('click', function() {
        if (confirm('¿Está seguro de limpiar todo el formulario?')) {
            document.getElementById('formUsuario').reset();
            empleadoCargado = tipoCargado = usuarioCargado = false;
            rutasCargadas = '';
            
            // Resetear badges
            document.getElementById('empleadoStatus').textContent = 'Nuevo';
            document.getElementById('empleadoStatus').className = 'badge badge-nuevo float-end';
            document.getElementById('tipoStatus').textContent = 'Nuevo';
            document.getElementById('tipoStatus').className = 'badge badge-nuevo float-end';
            document.getElementById('usuarioStatus').textContent = 'Nuevo';
            document.getElementById('usuarioStatus').className = 'badge badge-nuevo float-end';
            
            // Resetear rutas
            document.querySelectorAll('.ruta-checkbox').forEach(cb => cb.checked = false);
            actualizarRutasSeleccionadas();
            
            updateResumen();
            
            showMessage('info', 'Formulario limpiado');
        }
    });

    // ====================================
    // 8. VALIDACIÓN DEL FORMULARIO
    // ====================================
    document.getElementById('formUsuario').addEventListener('submit', function(e) {
        // Mostrar confirmación
        const confirmMessage = `
¿Confirmar operación?

Empleado: ${empleadoCargado ? 'EXISTENTE (se actualizarán datos)' : 'NUEVO'}
Tipo: ${tipoCargado ? 'EXISTENTE' : 'NUEVO'}
Usuario: ${usuarioCargado ? 'EXISTENTE (se actualizarán credenciales)' : 'NUEVO'}

${empleadoCargado ? 'NOTA: Se actualizarán sueldo, teléfono y dirección del empleado' : ''}
${usuarioCargado ? 'NOTA: Se actualizarán email y contraseña del usuario' : ''}
        `;
        
        if (!confirm(confirmMessage.trim())) {
            e.preventDefault();
        }
    });

    // ====================================
// 9. SISTEMA DE RUTAS (COMPATIBLE CON TIPOE)
// ====================================
// Mapeo de rutas a sus iniciales
const rutasMap = {
    'Reportes': 'R',
    'Usuarios': 'U', 
    'Clientes': 'C',
    'Empleados': 'E',
    'Ventas': 'V',
    'Almacenes': 'A',
    'Productos': 'P'
};

// Función para actualizar rutas seleccionadas (igual que en TipoE)
window.actualizarRutasSeleccionadas = function() {
    const rutasSeleccionadas = [];
    const iniciales = [];
    
    document.querySelectorAll('.ruta-checkbox').forEach(checkbox => {
        if (checkbox.checked) {
            const rutaNombre = checkbox.getAttribute('data-nombre');
            rutasSeleccionadas.push(rutaNombre);
            if (rutasMap[rutaNombre]) {
                iniciales.push(rutasMap[rutaNombre]);
            }
        }
    });
    
    // ORDENAR LAS INICIALES para consistencia
    iniciales.sort();
    
    // Actualizar contenedor de visualización
    const container = document.getElementById('rutasSeleccionadas');
    const buttonText = document.getElementById('rutasButtonText');
    const hiddenInput = document.getElementById('rutasInputHidden');
    
    container.innerHTML = '';
    
    if (rutasSeleccionadas.length > 0) {
        // Ordenar también las rutas
        rutasSeleccionadas.sort();
        
        rutasSeleccionadas.forEach(ruta => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-2 mb-2';
            badge.innerHTML = `${ruta} <span class="ms-1" style="cursor:pointer;" onclick="eliminarRuta('${ruta}')">&times;</span>`;
            container.appendChild(badge);
        });
        
        // Mostrar iniciales ordenadas y separadas por espacio (como en TipoE)
        hiddenInput.value = iniciales.join(' ');
        
        // Actualizar texto del botón
        buttonText.textContent = `${rutasSeleccionadas.length} ruta(s) seleccionada(s)`;
        
        // Actualizar resumen
        document.getElementById('resumenRutas').textContent = rutasSeleccionadas.join(', ');
    } else {
        buttonText.textContent = 'Elige rutas';
        hiddenInput.value = '';
        document.getElementById('resumenRutas').textContent = 'Ninguna';
    }
};

// Función para eliminar ruta desde la badge
window.eliminarRuta = function(rutaNombre) {
    const checkbox = document.querySelector(`.ruta-checkbox[data-nombre="${rutaNombre}"]`);
    if (checkbox) {
        checkbox.checked = false;
        window.actualizarRutasSeleccionadas();
    }
};

// Función mejorada para cargar rutas desde tipo
function cargarRutasDesdeTipo(rutasString) {
    if (!rutasString) return;
    
    // Limpiar todas las selecciones
    document.querySelectorAll('.ruta-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Convertir string de rutas a array (separado por espacio como en TipoE)
    const rutasArray = rutasString.trim().split(' ').filter(r => r);
    
    // Mapeo inverso: inicial -> ID del checkbox
    const mapeoCheckbox = {
        'R': 'ruta-reportes',
        'U': 'ruta-usuarios',
        'C': 'ruta-clientes',
        'E': 'ruta-empleados',
        'V': 'ruta-ventas',
        'A': 'ruta-almacenes',
        'P': 'ruta-productos'
    };
    
    // Marcar checkboxes correspondientes
    rutasArray.forEach(inicial => {
        if (inicial && mapeoCheckbox[inicial]) {
            const checkbox = document.getElementById(mapeoCheckbox[inicial]);
            if (checkbox) {
                checkbox.checked = true;
            }
        }
    });
    
    // Actualizar visualización de rutas
    setTimeout(() => {
        if (typeof window.actualizarRutasSeleccionadas === 'function') {
            window.actualizarRutasSeleccionadas();
        }
    }, 100);
    
    rutasCargadas = rutasString;
}

// Asignar evento a checkboxes de rutas
document.querySelectorAll('.ruta-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', window.actualizarRutasSeleccionadas);
});

// Mantener dropdown abierto al hacer clic en checkboxes
const dropdownMenu = document.querySelector('.dropdown-menu');
if (dropdownMenu) {
    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

    // Inicializar resumen
function updateResumen() {
    document.getElementById('resumenEmpleado').textContent = empleadoCargado ? 'Existente' : 'Nuevo';
    document.getElementById('resumenTipo').textContent = tipoCargado ? 'Existente' : 'Nuevo';
    document.getElementById('resumenUsuario').textContent = usuarioCargado ? 'Existente' : 'Nuevo';
    
    // Actualizar rutas en resumen usando la función de rutas
    if (typeof window.actualizarRutasSeleccionadas === 'function') {
        // Solo actualizar el texto del resumen
        const rutasSeleccionadas = document.querySelectorAll('.ruta-checkbox:checked');
        if (rutasSeleccionadas.length > 0) {
            const nombresRutas = Array.from(rutasSeleccionadas).map(cb => {
                return cb.getAttribute('data-nombre');
            }).join(', ');
            document.getElementById('resumenRutas').textContent = nombresRutas;
        } else {
            document.getElementById('resumenRutas').textContent = 'Ninguna';
        }
    }
}
});
</script>

@endsection