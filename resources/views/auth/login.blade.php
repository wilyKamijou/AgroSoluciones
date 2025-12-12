<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroSoluciones - Sistema de Gestión</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
    
    <!-- Meta Tags -->
    <meta name="description" content="Sistema de gestión agropecuaria AgroSoluciones">
    <meta name="author" content="AgroSoluciones">
</head>
<body class="hold-transition login-page">
    <!-- Efecto de partículas -->
    <div class="particles"></div>
    
    <!-- Contenedor principal -->
    <div class="login-box">
        <!-- Logo -->
        <div class="login-logo">
            <div class="logo-container">
                <a href="{{ url('/') }}">
                    <i class="fas fa-seedling logo-icon"></i>
                    <b>Agro</b>Soluciones
                </a>
            </div>
        </div>
        
        <!-- Mensaje de configuración inicial -->
        @if(session('info'))
        <div class="alert alert-info setup-alert">
            <div class="setup-message">
                <i class="fas fa-info-circle setup-icon"></i>
                <div class="setup-content">
                    <h4>Configuración Inicial Requerida</h4>
                    <p>{{ session('info') }}</p>
                    <a href="{{ route('register') }}" class="setup-btn">
                        <i class="fas fa-user-plus"></i>
                        Crear Primera Cuenta
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Card del formulario -->
        <div class="card login-card">
            <div class="card-body login-card-body">
                <!-- Título -->
                <h2 class="login-title">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Acceso al Sistema
                </h2>
                
                <!-- Mensaje de error -->
                @if($errors->any())
                <div class="error-message show">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Credenciales incorrectas. Por favor, verifica tus datos.
                </div>
                @endif
                
                <!-- Formulario -->
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <!-- Campo Email -->
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" 
                               name="email" 
                               class="input-field @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" 
                               placeholder="Correo electrónico"
                               required
                               autocomplete="email"
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Campo Contraseña -->
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               class="input-field @error('password') is-invalid @enderror"
                               placeholder="Contraseña"
                               required
                               autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Recordar sesión -->
                    <div class="remember-container">
                        <div class="custom-checkbox {{ old('remember') ? 'checked' : '' }}"></div>
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="d-none" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="remember-label">
                            Recordar mis datos en este equipo
                        </label>
                    </div>
                    
                    <!-- Botón de envío -->
                    <div class="login-btn-container">
                        <button type="submit" class="login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="login-footer">
                        <small>
                            <i class="fas fa-shield-alt mr-1"></i>
                            Acceso restringido al personal autorizado
                        </small>
                        <br>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-clock mr-1"></i>
                            {{ date('Y') }} &copy; AgroSoluciones v{{ config('app.version', '1.0') }}
                        </small>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Mensaje de soporte -->
        <div class="text-center mt-4">
            <small class="text-light">
                <i class="fas fa-life-ring mr-1"></i>
                ¿Problemas para acceder? Contacta al administrador
            </small>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    <!-- Script personalizado -->
    <script src="{{ asset('js/auth/login.js') }}"></script>
    
    <!-- Script de inicialización -->
    <script>
        // Mostrar versión en consola (debug)
        console.log('%c🔐 AgroSoluciones Login v{{ config('app.version', '1.0') }}', 
            'color: #4c956c; font-size: 12px; font-weight: bold;');
        console.log('%c⚠️  Acceso autorizado únicamente', 
            'color: #dc3545; font-size: 10px;');
        
        // Verificar automáticamente si no hay usuarios
        document.addEventListener('DOMContentLoaded', function() {
            // Opcional: Puedes hacer una petición AJAX para verificar
            fetch('/api/check-users')
                .then(response => response.json())
                .then(data => {
                    if (data.users_count === 0) {
                        window.location.href = '/register';
                    }
                })
                .catch(error => {
                    console.log('Error verificando usuarios:', error);
                });
        });
    </script>
</body>
</html>