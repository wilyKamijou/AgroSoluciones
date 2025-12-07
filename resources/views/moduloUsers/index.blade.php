@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Modulo de Usuarios</h1>
</section>

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
                    <!-- Nombre empleado -->

                    <h4 class="mt-4">Tipo de Empleado</h4>
                    <hr>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Empleado</label>
                        <input type="text" name="nombreE" class="form-control" placeholder="Ej: Cajero, Gerente, Repartidor" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" placeholder="Descripcion de que hace el empelado" required>
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
@endsection