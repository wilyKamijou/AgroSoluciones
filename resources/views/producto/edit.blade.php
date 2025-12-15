@extends('home')

@section ("contenido")

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="ruta-a-tu-archivo.css">
<script src="/js/producto.js"></script>
<h2 class="text-center mb-4">Editar Datos Del Producto</h2>
<div class="card shadow-sm p-4 mb-4 card-compact">
    <form action="/producto/{{$producto->id_producto}}/actualizar" method="POST" class="row g-3">
        @method('PUT')
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- Nombre -->
        <div class="col-md-6">
            <label class="form-label">Nombre del Producto</label>
            <input type="text" name="nombrePr" class="form-control" value="{{$producto->nombrePr}}" required>
        </div>

        <!-- Nombre Técnico -->
        <div class="col-md-6">
            <label class="form-label">Nombre Técnico</label>
            <input type="text" name="nombreTecnico" class="form-control" value="{{$producto->nombreTecnico}}" required>
        </div>

        <!-- Descripción -->
        <div class="col-md-12">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcionPr" class="form-control" value="{{$producto->descripcionPr}}" required>
        </div>

        <!-- Composición Química -->
        <div class="col-md-6">
            <label class="form-label">Composición Química</label>
            <input type="text" name="compocicionQuimica" class="form-control" value="{{$producto->compocicionQuimica}}" required>
        </div>

        <!-- Concentración Química -->
        <div class="col-md-6">
            <label class="form-label">Concentración Química</label>
            <input type="text" name="consentracionQuimica" class="form-control" value="{{$producto->consentracionQuimica}}" required>
        </div>

        <!-- Fecha Fabricación -->
        <div class="col-md-4">
            <label class="form-label">Fecha de Fabricación</label>
            <input type="date" name="fechaFabricacion" class="form-control" value="{{$producto->fechaFabricacion}}" required>
        </div>

        <!-- Fecha Vencimiento -->
        <div class="col-md-4">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fechaVencimiento" class="form-control" value="{{$producto->fechaVencimiento}}" required>
        </div>

        <!-- Unidad de Medida -->
        <div class="col-md-4">
            <label class="form-label">Unidad de Medida</label>
            <input type="text" name="unidadMedida" class="form-control" value="{{$producto->unidadMedida}}" required>
        </div>

        <!-- Categoría -->
        <div class="col-md-6">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" class="form-control" required>
                <option value="" disabled selected>Seleccione la categoría</option>
                @foreach ($categorias as $categoria)
                <option value="{{$categoria->id_categoria}}">{{$categoria->nombreCat}}</option>
                @endforeach
            </select>
        </div>
        <!-- precio -->
        <div class="col-md-4">
            <label class="form-label">Precio del producto</label>
            <input type="text" name="precioPr" class="form-control" value="{{$producto->precioPr}}" required>
        </div>
        <!-- URL de la Imagen -->
        <div class="col-mb-5">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="imagen_url" class="form-label">
                    <i class="fas fa-image me-1 text-muted"></i>URL de la Imagen
                </label>
                <div>
                    <button type="button" id="buscarImagenPixabay" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i> Buscar automático
                    </button>
                    <button type="button" id="buscarManualPixabay" class="btn btn-sm btn-outline-info me-2">
                        <i class="fas fa-external-link-alt me-1"></i> Pixabay
                    </button>
                    <button type="button" id="buscarManualUnsplash" class="btn btn-sm btn-outline-info me-2">
                        <i class="fas fa-external-link-alt me-1"></i> Unsplash
                    </button>
                    <button type="button" id="previsualizarImagen" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye me-1"></i> Previsualizar
                    </button>
                </div>
            </div>
            <input type="url" id="imagen_url" name="imagen_url" class="form-control" value="{{$producto->imagen_url}}" required>
            <small class="text-muted">Busca imágenes profesionales o ingresa una URL</small>
            <div class="invalid-feedback">
                Por favor ingresa una URL válida de imagen.
            </div>
            <div id="imagenPrevisualizacion" class="mt-3 text-center d-none">
                <img src="" alt="Previsualización" class="img-thumbnail" style="max-height: 200px;">
                <div class="mt-2 text-muted small">Previsualización de la imagen</div>
                <div id="creditosImagen" class="text-muted smaller mt-1"></div>
            </div>
        </div>
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/producto" class="btn btn-secondary">Cancelar</a>
        </div>
</div>

</form>
</div>

@endsection