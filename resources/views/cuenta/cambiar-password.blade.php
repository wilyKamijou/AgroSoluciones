@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Cambiar Contraseña</h1>
</section>

<div class="center-wrapper">
    <section class="content" style="min-height: auto; padding: 0;">
        <!-- Card de Cambio de Contraseña -->
        <div class="card shadow-sm p-4" style="margin-bottom: 0;">
            <h4 class="mb-3">Actualizar Contraseña</h4>
            
            <form action="{{ route('cuenta.actualizar-password') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <!-- Contraseña Actual -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Contraseña Actual</label>
                        <input type="password" name="password_actual" class="form-control" placeholder="Ingrese su contraseña actual" required>
                        @error('password_actual')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Nueva Contraseña -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" name="nuevo_password" class="form-control" placeholder="Ingrese nueva contraseña" required minlength="8">
                        @error('nuevo_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirmar Contraseña -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Confirmar Contraseña</label>
                        <input type="password" name="nuevo_password_confirmation" class="form-control" placeholder="Confirme la nueva contraseña" required>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Contraseña
                        </button>
                        <a href="{{ route('cuenta.perfil') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al Perfil
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>  

<!-- Mostrar mensajes de éxito/error -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert" style="margin-bottom: 20px;">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert" style="margin-bottom: 20px;">
    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@endsection