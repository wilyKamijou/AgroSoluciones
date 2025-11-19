@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Producto</h2>
    <form action="/producto/{{$producto->id_producto}}/actualizar" method="POST">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre del producto:</label>
            <input type="text" id="nombrePr" name="nombrePr" class="form-control" value="{{$producto->nombrePr}}" required>
        </div>

        <!-- NombreTecnico -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre tecnico del producto:</label>
            <input type="text" id="nombrePr" name="nombreTecnico" class="form-control" value="{{$producto->nombreTecnico}}" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion del producto:</label>
            <input type="text" id="ubicacionPr" name="descripcionPr" class="form-control" value="{{$producto->descripcionPr}}" requiered>
        </div>

           <!-- compocicionQuimica-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Composicion quimica del producto:</label>
            <input type="text" id="ubicacion" name="compocicionQuimica" class="form-control" value="{{$producto->compocicionQuimica}}" required>
        </div>

           <!-- consentracionQuimica-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Consentracion quimica del producto:</label>
            <input type="text" id="ubicacion" name="consentracionQuimica" class="form-control" value="{{$producto->consentracionQuimica}}" required>
        </div>

           <!-- fecha de fabricacion-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Fecha de fabricacion del producto:</label>
            <input type="date" id="ubicacion" name="fechaFabricacion" class="form-control" value="{{$producto->fechaFabricacion}}" required>
        </div>

           <!-- fecha de vencimiento-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Fecha de vencimiento del producto:</label>
            <input type="date" id="ubicacion" name="fechaVencimiento" class="form-control" value="{{$producto->fechaVencimiento}}" required>
        </div>

           <!-- unidad de medida-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Unidad de medida del producto:</label>
            <input type="text" id="ubicacion" name="unidadMedida" class="form-control" value="{{$producto->unidadMedida}}" required>
        </div>

           <!-- Tipo de producto -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Tipo de categoria:</label>
                <select id="id_tipoE" name="id_categoria" class="form-select" required>
                    <option value="" disabled selected>Seleccione el tipo de categoria del producto</option>
                    @foreach ($categorias as $categoria)
                       <option value={{$categoria->id_categoria}}> {{$categoria->nombreCat}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/producto" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
