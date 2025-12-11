@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e1f0ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b7ddd;
        font-weight: bold;
        font-size: 14px;
    }

    .bg-light-primary {
        background-color: rgba(59, 125, 221, 0.1) !important;
    }

    .user-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Usuarios</h1>
</section>

<div class="center-wrapper">
    <section class="content">
        <!-- Card de Registrar Usuario -->
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-3">Registrar Nuevo Usuario</h4>

            <form action="/user/guardar" method="POST" class="row g-3">
                @csrf

                <!-- Nombre -->
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" placeholder="Ingrese el nombre del usuario" required>
                </div>

                <!-- Correo -->
                <div class="col-md-4">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="usuario@dominio.com" required>
                </div>

                <!-- Contraseña -->
                <div class="col-md-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required minlength="8">
                </div>

                <!-- Botones -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>

                </div>
            </form>
        </div>

        <!-- Card Tabla -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Usuarios</h4>

                <div class="d-flex gap-2 align-items-center">
                    <!-- Buscador -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o correo...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>



                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>

                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="user-row {{ $user->id === Auth::id() ? 'bg-light-primary' : '' }}">
                            <td><strong>#{{ $user->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <span>{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">Registro: {{ $user->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $user->email }}</div>
                                <small class="text-muted">
                                    Último acceso: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                                </small>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Activo
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="/user/{{ $user->id }}/editar" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($user->id !== Auth::id())
                                <form action="/user/{{ $user->id }}/eliminar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection