@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-users-cog text-primary me-2"></i>Administración de Usuarios
                </h3>

                <div>

                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/user/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Usuario
                    </a>
                </div>

            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 25%">Usuario</th>
                            <th style="width: 25%">Correo</th>
                            <th style="width: 15%">Rol</th>
                            <th style="width: 10%">Estado</th>
                            <th style="width: 20%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="{{ $user->id === Auth::id() ? 'bg-light-primary' : '' }}">
                            <td class="fw-bold text-muted">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">Registro: {{ $user->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $user->email }}</div>
                                <small class="text-muted">Último acceso: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger-light text-danger' : 'bg-primary-light text-primary' }}">
                                    <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} me-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success-light text-success">
                                    <i class="fas fa-check-circle me-1"></i> Activo
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/user/{{ $user->id }}/editar" class="btn btn-outline-primary rounded-start" data-bs-toggle="tooltip" title="Editar usuario">
                                        <i class="fas fa-pen"></i>
                                    </a>


                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-center py-3">
                <div class="text-muted">
                    Total de usuarios registrados: {{ count($users) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Confirmar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                ¿Estás seguro de realizar esta acción?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Formularios ocultos -->
<form id="actionForm" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('css')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #e1f0ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b7ddd;
        font-weight: bold;
    }

    .bg-light-primary {
        background-color: rgba(59, 125, 221, 0.1);
    }

    .bg-primary-light {
        background-color: rgba(59, 125, 221, 0.1);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.1);
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush

@push('js')@push('js')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Función para cambiar rol (manteniendo la existente)
    function changeRole(userId, currentRole) {
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        document.getElementById('modalTitle').textContent = 'Cambiar rol de usuario';
        document.getElementById('modalBody').textContent = `¿Estás seguro de ${currentRole === 'admin' ? 'quitar privilegios de administrador' : 'convertir en administrador'} a este usuario?`;

        document.getElementById('confirmAction').onclick = function() {
            const form = document.getElementById('actionForm');
            form.action = `/user/${userId}/cambiar-rol`;
            form.submit();
        };

        modal.show();
    }

    // Función corregida para eliminar usuario
    function confirmDelete(userId) {
        const modalElement = document.getElementById('confirmModal');
        const modal = new bootstrap.Modal(modalElement);

        // Configurar el contenido del modal
        document.getElementById('modalTitle').textContent = 'Eliminar usuario';
        document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.';

        // Limpiar eventos previos
        const confirmBtn = document.getElementById('confirmAction');
        const cancelBtn = modalElement.querySelector('.btn-secondary');

        // Limpiar eventos anteriores
        confirmBtn.onclick = null;
        cancelBtn.onclick = null;

        // Configurar nuevo evento para Confirmar
        confirmBtn.onclick = function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/user/${userId}/eliminar`;

            // Añadir CSRF token y método DELETE
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();

            modal.hide();
        };

        // Configurar evento para Cancelar
        cancelBtn.onclick = function() {
            modal.hide();
        };

        // Mostrar el modal
        modal.show();
    }
</script>
@endpush