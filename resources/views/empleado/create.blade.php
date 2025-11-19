@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Empleado</h2>
    <form action="/empleado/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombreEm" class="form-label">Nombre:</label>
            <input type="text" id="nombreEm" name="nombreEm" class="form-control" placeholder="Ingrese el nombre del empleado" required>
        </div>

        <!-- Apellidos -->
        <div class="mb-3">
            <label for="apellidosEm" class="form-label">Apellidos:</label>
            <input type="text" id="apellidosEm" name="apellidosEm" class="form-control" placeholder="Ingrese los apellidos del empleado" required>
        </div>

        <!-- Sueldo -->
        <div class="mb-3">
            <label for="sueldoEm" class="form-label">Sueldo:</label>
            <input type="number" id="sueldoEm" name="sueldoEm" class="form-control" placeholder="Ingrese el sueldo del empleado" required>
        </div>
        
        <!-- Telefono -->
        <div class="mb-3">
            <label for="telefonoEm" class="form-label">Telefono:</label>
            <input type="number" id="telefonoEm" name="telefonoEm" class="form-control" placeholder="Ingrese el Telefono del empleado" required>
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingrese la dirección del empleado" required>
        </div>

        <!-- Tipo de Empleado -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Tipo de Empleado:</label>
            <select id="id_tipoE" name="id_tipoE" class="form-select" required>
                <option value="" disabled selected>Seleccione el tipo de empleado</option>
                @foreach ($tipos as $tipo)
                    <option value={{$tipo->id_tipoE}}> {{$tipo->descripcionTip}} </option>
                @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
            </select>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/empleado" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
