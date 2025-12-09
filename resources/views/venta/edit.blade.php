@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">

<h2 style="font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Venta</h2>
<div class="card shadow-sm p-4 mb-4">
    <form action="/venta/{{$venta->id_venta}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Fecha -->
        <div class="col-md-6">
            <label class="form-label">Fecha de la venta</label>
            <input type="date" name="fechaVe" class="form-control" value="{{$venta->fechaVe}}" required>
        </div>

        <!-- Monto total -->
        <div class="col-md-6">
            <label class="form-label">Monto Total</label>
            <input type="text" step="0.01" name="montoTotalVe" class="form-control" value="{{$venta->montoTotalVe}}" required>
        </div>

        <!-- Cliente -->
        <div class="col-md-6">
            <label class="form-label">Cliente de la venta</label>
            <select name="id_cliente" class="form-control" required>
                <option value="" disabled selected>Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id_cliente }}">
                    {{ $cliente->nombreCl }} {{ $cliente->apellidosCl }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Empleado -->
        <div class="col-md-6">
            <label class="form-label">Empleado que registró la venta</label>
            <select name="id_empleado" class="form-control" required>
                <option value="" disabled selected>Seleccione un empleado</option>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->id_empleado }}">
                    {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                </option>
                @endforeach
            </select>
        </div>


        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/venta" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection