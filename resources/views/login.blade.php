<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 0.875em;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
</style>
</head>
<body>
<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form id="loginForm" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
        </div>
        <div class="form-group">
            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>
        </div>
        <button type="submit" class="login-btn">Entrar</button>
        @if ($errors->any())
            <p class="error">{{ $errors->first('usuario') }}</p>
        @endif
    </form>
</div>

<script>
    const form = document.getElementById('loginForm');
    const errorElement = document.querySelector('.error');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Evita el envío del formulario para manejo personalizado
        const usuario = document.getElementById('usuario').value;
        const contraseña = document.getElementById('contraseña').value;

        // Aquí puedes agregar la lógica de validación
        if (usuario === "admin" && contraseña === "1234") {
            alert("Bienvenido, " + usuario + "!");
            errorElement.style.display = 'none';
            // Redirigir a otra página si es necesario
        } else {
            errorElement.style.display = 'block';
        }
    });
</script>
</body>
</html>
