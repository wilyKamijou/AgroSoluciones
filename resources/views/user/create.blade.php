@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-user-plus me-2"></i>Registrar Nuevo Usuario
                </h2>
                <div>
                    <a href="/user" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-chevron-left me-1"></i> Volver a usuarios
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="/user/guardar" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-muted"></i>Nombre del usuario
                            </label>
                            <input type="text" id="nombre" name="name" class="form-control" placeholder="Ej: Juan Pérez" required>
                            <div class="invalid-feedback">
                                Por favor ingresa el nombre del usuario.
                            </div>
                        </div>

                        <!-- Correo -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="email" class="form-label fw-semibold mb-0">
                                    <i class="fas fa-envelope me-1 text-muted"></i>Correo electrónico
                                </label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addDomain('@gmail.com')">@gmail.com</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addDomain('@yahoo.com')">@yahoo.com</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addDomain('@hotmail.com')">@hotmail.com</button>
                                </div>
                            </div>
                            <input type="email" id="email" name="email" class="form-control" placeholder="usuario@dominio.com" required>
                            <div class="invalid-feedback">
                                Por favor ingresa un correo electrónico válido.
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <!-- Contraseña -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label fw-semibold mb-0">
                                    <i class="fas fa-lock me-1 text-muted"></i>Contraseña
                                </label>
                                <button type="button" id="generarPassword" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-key me-1"></i> Generar contraseña
                                </button>
                            </div>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                La contraseña debe tener al menos 8 caracteres.
                            </div>
                        </div>

                        <!-- Rol -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-tag me-1 text-muted"></i>Rol del usuario
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="role" id="role_admin" value="admin" checked>
                                <label class="btn btn-outline-primary" for="role_admin">
                                    <i class="fas fa-user-shield me-1"></i> Administrador
                                </label>

                                <input type="radio" class="btn-check" name="role" id="role_cliente" value="cliente">
                                <label class="btn btn-outline-primary" for="role_cliente">
                                    <i class="fas fa-user me-1"></i> Cliente
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .form-label {
        font-weight: 500;
    }

    .invalid-feedback {
        font-size: 0.85rem;
    }

    .was-validated .form-control:invalid,
    .was-validated .form-select:invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .was-validated .form-control:valid,
    .was-validated .form-select:valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(0, 0, 0, .1);
        border-radius: 50%;
        border-top-color: #0d6efd;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@push('js')
<script>
    // Validación del formulario
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })();

    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Generar contraseña segura
    document.getElementById('generarPassword').addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
        let password = '';

        // Aseguramos que la contraseña tenga al menos un número y un carácter especial
        password += getRandomChar('0123456789');
        password += getRandomChar('!@#$%^&*()_+');

        // Completamos hasta 10 caracteres
        for (let i = 0; i < 8; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        // Mezclamos los caracteres
        password = password.split('').sort(() => 0.5 - Math.random()).join('');

        document.getElementById('password').value = password;

        // Mostrar notificación
        showToast('success', 'Contraseña generada', 'Se ha creado una contraseña segura');
    });

    function getRandomChar(charSet) {
        return charSet.charAt(Math.floor(Math.random() * charSet.length));
    }

    // Añadir dominio al correo
    function addDomain(domain) {
        const emailInput = document.getElementById('email');
        const currentValue = emailInput.value.split('@')[0];
        emailInput.value = currentValue + domain;
        emailInput.focus();
    }

    // Mostrar notificación
    function showToast(type, title, message) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '11';

        let headerClass = type === 'success' ? 'bg-success text-white' :
            type === 'error' ? 'bg-danger text-white' : 'bg-primary text-white';

        toast.innerHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${headerClass}">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;

        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
</script>
@endpush