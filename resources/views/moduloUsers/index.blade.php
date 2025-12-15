@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/rutas.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<h1 class="text-center mb-4">Modulo de Usuarios</h1>
<section class="content">
    <div class="center-wrapper">
        <section class="content">

            <div class="card shadow-sm p-4 mb-4">
                <form action="/mUser/guardar" method="POST" class="row g-3">
                    @csrf

                    <!-- ======================= -->
                    <!--   SECCIÓN: EMPLEADO    -->
                    <!-- ======================= -->
                    <h4 class="mt-4">Datos del Empleado</h4>
                    <hr>

                    <!-- Nombre empleado -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombreEm" class="form-control" placeholder="Ingrese el nombre del empleado" required>
                    </div>

                    <!-- Apellidos -->
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidosEm" class="form-control" placeholder="Ingrese los apellidos" required>
                    </div>

                    <!-- Sueldo -->
                    <div class="col-md-6">
                        <label class="form-label">Sueldo</label>
                        <input type="text" name="sueldoEm" class="form-control" placeholder="Ingrese sueldo" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefonoEm" class="form-control" placeholder="Ingrese teléfono" required>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Ingrese dirección" required>
                    </div>

                    <!-- ==================================== -->
                    <!--   SECCIÓN: TIPO DE EMPLEADO         -->
                    <!-- ==================================== -->
                    <h4 class="mt-4">Tipo de Empleado</h4>
                    <hr>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Empleado</label>
                        <input type="text" name="nombreE" class="form-control" placeholder="Ej: Cajero, Gerente, Repartidor" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" placeholder="Descripción de que hace el empleado" required>
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
                    <h4 class="mt-3">Datos del Usuario</h4>
                    <hr>

                    <!-- Nombre -->
                    <div class="col-md-4">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="name" class="form-control" placeholder="Ingrese nombre del usuario" required>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-4">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="usuario@dominio.com" required>
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="8" placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <!-- BOTÓN FINAL -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-lg">Guardar Todo</button>
                    </div>
                </form>
            </div>

        </section>
    </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/rutas.js"></script>

@endsection