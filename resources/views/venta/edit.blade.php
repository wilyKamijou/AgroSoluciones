@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center" >Editar Datos De La Venta</h2>
    <form action="/venta/{{$venta->id_venta}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <!-- fecha -->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Fecha de la venta:</label>
            <input type="date" id="ubicacion" name="fechaVe" class="form-control" value="{{$venta->fechaVe}}" required>
        </div>

           <!--monto total-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Monto total de la venta:</label>
            <input type="text" id="ubicacion" name="montoTotalVe" class="form-control" value="{{$venta->montoTotalVe}}" required>
        
                     <!-- Tipo de proveedor -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Cliente de la venta:</label>
                <select id="id_tipoE" name="id_cliente" class="form-select" required>
                    <option value="" disabled selected>Seleccione al cliente de la venta</option>
                    @foreach ($clientes as $cliente)
                       <option value={{$cliente->id_cliente}}> {{$cliente->nombreCl}} {{$cliente->apellidosCl}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>


           <!-- Tipo de empleado -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Empelado que registro la venta:</label>
                <select id="id_tipoE" name="id_empleado" class="form-select" required>
                    <option value="" disabled selected>Seleccione al empleado</option>
                    @foreach ($empleados as $empleado)
                       <option value={{$empleado->id_empleado}}> {{$empleado->nombreEm}} {{$empleado->apellidosEm}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>
  
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/venta" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
