@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">
<div class="card shadow-sm p-4 mb-4">
    <h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Empleado</h2>
    <form action="/empleado/{{$empleado->id_empleado}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- Nombre -->
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombreEm" class="form-control" value="{{$empleado->nombreEm }}" required>
        </div>

        <!-- Apellidos -->
        <div class="col-md-6">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidosEm" class="form-control" value="{{$empleado->apellidosEm}}" required>
        </div>

        <!-- Sueldo -->
        <div class="col-md-6">
            <label class="form-label">Sueldo</label>
            <input type="number" name="sueldoEm" class="form-control" value="{{$empleado->sueldoEm}}" required>
        </div>

        <!-- Telefono -->
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="number" name="telefonoEm" class="form-control" value="{{$empleado->telefonoEm}}" required>
        </div>

        <!-- Dirección -->
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{$empleado->direccion}}" required>
        </div>

        <!-- Tipo de Empleado -->
        <div class="col-md-6">
            <label class="form-label">Tipo de Empleado</label>
            <select name="id_tipoE" class="form-control" required>
                <option value="" disabled selected>Seleccione el tipo de empleado</option>
                @foreach ($tipos as $tipo)
                <option value="{{$tipo->id_tipoE}}">{{$tipo->descripcionTip}}</option>
                @endforeach
            </select>
        </div>

        <!-- Cuenta -->
        <div class="col-md-6">
            <label class="form-label">Cuenta</label>
            <select name="user_id" class="form-control" required>
                <option value="" disabled selected>Seleccione la cuenta</option>
                @foreach ($cuentas as $cuenta)
                <option value="{{$cuenta->id}}">{{$cuenta->email}}</option>
                @endforeach
            </select>
        </div>
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/empleado" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection