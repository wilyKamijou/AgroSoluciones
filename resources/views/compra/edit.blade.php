@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center" >Editar Datos De La Compra</h2>
    <form action="/compra/{{$compra->id_compra}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

           <!-- fecha -->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Fecha de la compra:</label>
            <input type="date" id="ubicacion" name="fechaCo" class="form-control" value="{{$compra->fechaCo}}" required>
        </div>

           <!--monto total-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Monto total de la compra:</label>
            <input type="text" id="ubicacion" name="montoTotalCo" class="form-control" value="{{$compra->montoRotalCo}}" required>
        </div>

           <!-- Tipo de empleado -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Empelado que registro la compra:</label>
                <select id="id_tipoE" name="id_empleado" class="form-select" required>
                    <option value="" disabled selected>Seleccione al empleado</option>
                    @foreach ($empleados as $empleado)
                       <option value={{$empleado->id_empleado}}> {{$empleado->nombreEm}} {{$empleado->apellidosEm}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>
           <!-- Tipo de proveedor -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Poveedor de la compra:</label>
                <select id="id_tipoE" name="id_proveedor" class="form-select" required>
                    <option value="" disabled selected>Seleccione al proveedor</option>
                    @foreach ($proveedors as $proveedor)
                       <option value={{$proveedor->id_proveedor}}> {{$proveedor->nombrePr}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/compra" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
