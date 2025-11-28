@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Mi Perfil</h1>
</section>

<div class="center-wrapper">
    <section class="content">
        <!-- Card de Información de la Cuenta -->
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-3">Información de la Cuenta</h4>
            
            <div class="row g-3">
                <!-- Información del Usuario -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email</label>
                    <div class="form-control bg-light">
                        {{ $usuario->email }}
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha de Registro</label>
                    <div class="form-control bg-light">
                        {{ $usuario->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Información del Empleado -->
        @if($empleado)
        <div class="card shadow-sm p-4">
            <h4 class="mb-3">Información del Empleado</h4>
            
            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre</label>
                    <div class="form-control bg-light">
                        {{ $empleado->nombreEm }}
                    </div>
                </div>
                
                <!-- Apellidos -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Apellidos</label>
                    <div class="form-control bg-light">
                        {{ $empleado->apellidosEm }}
                    </div>
                </div>
                
                <!-- Sueldo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Sueldo</label>
                    <div class="form-control bg-light">
                        ${{ number_format($empleado->sueldoEm, 2) }}
                    </div>
                </div>
                
                <!-- Teléfono -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Teléfono</label>
                    <div class="form-control bg-light">
                        {{ $empleado->telefonoEm }}
                    </div>
                </div>
                
                <!-- Dirección -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Dirección</label>
                    <div class="form-control bg-light">
                        {{ $empleado->direccion }}
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow-sm p-4">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle"></i>
                No se encontró información de empleado asociada a esta cuenta.
            </div>
        </div>
        @endif

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-between mt-4">
            <a href="/home" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Inicio
            </a>
            <a href="{{ route('cuenta.password') }}" class="btn btn-primary">
                <i class="bi bi-lock"></i> Cambiar Contraseña
            </a>
        </div>
    </section>
</div>

<!-- Mostrar mensajes de éxito/error -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@endsection