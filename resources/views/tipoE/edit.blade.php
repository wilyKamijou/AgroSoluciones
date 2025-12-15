@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">
<script src="/js/rutas.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<h2 class="text-center mb-4">Editar Datos De Un Tipo De Empleado</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">

    <form action="/tipo/{{$tipo->id_tipoE}}/actualizar" method="POST" class="compact-form">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row g-3">
            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Nombre del Tipo</label>
                <input type="text" name="nombreE" class="form-control" value="{{$tipo->nombreE}}" required>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Descripción del Tipo</label>
                <input type="text" name="descripcionTip" class="form-control" value="{{$tipo->descripcionTip}}" required>
            </div>
            <!--rutas-->
            <div class="mb-3">
                <label class="form-label">Selecciona Rutas:</label>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownRutas" data-bs-toggle="dropdown" aria-expanded="false">
                        Elige rutas
                    </button>
                    <ul class="dropdown-menu p-3" aria-labelledby="dropdownRutas" style="max-height: 200px; overflow-y: auto;">
                        @foreach($rutas as $ruta)
                        <li>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input ruta-checkbox" name="rutas[]" value="{{ $ruta->id_ruta }}" {{ in_array($ruta->id_ruta, $rutasAsignadas) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ruta-{{ $ruta->id_ruta }}">
                                    {{ $ruta->nombreR }}
                                </label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Botones -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/tipo" class="btn btn-secondary">Cancelar</a>
            </div>
    </form>
</div>

@endsection