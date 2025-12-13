@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="/js/producto.js"></script>


<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Productos</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Producto -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Producto</h4>

            <form action="/producto/guardar" method="POST" class="compact-form">
                @csrf

                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Producto</label>
                        <input type="text" name="nombrePr" class="form-control" placeholder="Ingrese el nombre del producto" required>
                    </div>

                    <!-- Nombre Técnico -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre Técnico</label>
                        <input type="text" name="nombreTecnico" class="form-control" placeholder="Ingrese el nombre técnico del producto" required>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcionPr" class="form-control" placeholder="Ingrese la descripción del producto" required>
                    </div>

                    <!-- Composición Química -->
                    <div class="col-md-6">
                        <label class="form-label">Composición Química</label>
                        <input type="text" name="compocicionQuimica" class="form-control" placeholder="Ingrese la composición química" required>
                    </div>

                    <!-- Concentración Química -->
                    <div class="col-md-6">
                        <label class="form-label">Concentración Química</label>
                        <input type="text" name="consentracionQuimica" class="form-control" placeholder="Ingrese la concentración química" required>
                    </div>

                    <!-- Fecha Fabricación -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha de Fabricación</label>
                        <input type="date" name="fechaFabricacion" class="form-control" required>
                    </div>

                    <!-- Fecha Vencimiento -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha de Vencimiento</label>
                        <input type="date" name="fechaVencimiento" class="form-control" required>
                    </div>

                    <!-- Unidad de Medida -->
                    <div class="col-md-4">
                        <label class="form-label">Unidad de Medida</label>
                        <input type="text" name="unidadMedida" class="form-control" placeholder="Ej: kg, litros, unidades" required>
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
                        <input type="text" name="precioPr" class="form-control" placeholder="ingrese el precio del producto" required>
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
                        <input type="url" id="imagen_url" name="imagen_url" class="form-control" placeholder="https://pixabay.com/images/id-12345/" required>
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
                </div>

                <!-- Botones -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
        </div>
        </form>
</div>

<!-- Card Tabla -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Lista de Productos
        <small id="resultCount" class="text-muted fs-6 ms-2">
            ({{ count($productos) }} productos)
        </small>
    </h4>
    <div class="d-flex gap-2 align-items-center">
        <!-- Barra de búsqueda -->

        <div class="flex-grow-1" style="max-width: 300px;">
            <input type="text" id="searchProductos" class="form-control" placeholder="Buscar por nombre, descripción, categoría...">
        </div>

        <!-- Botón simple de PDF -->
        <a href="{{ url('/empleado/pdf') }}" class="btn btn-danger btn-sm">
            PDF
        </a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="productosTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Nombre Técnico</th>
                <th>Descripción</th>
                <th>Composición Química</th>
                <th>Concentración Química</th>
                <th>Fecha fabricación</th>
                <th>Fecha Vencimiento</th>
                <th>Unidad medida</th>
                <th>Categoría</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            @php
            $categoria = $categorias->firstWhere('id_categoria', $producto->id_categoria);
            $hoy = now();
            $vencimiento = \Carbon\Carbon::parse($producto->fechaVencimiento);
            $diasParaVencer = $hoy->diffInDays($vencimiento, false);
            @endphp
            <tr class="producto-row">
                <td><strong>{{$producto->id_producto}}</strong></td>
                <td>{{$producto->nombrePr}}</td>
                <td>{{$producto->precioPr}}Bs.</td>
                <td>{{$producto->nombreTecnico}}</td>
                <td>
                    <span class="texto-largo" title="{{$producto->descripcionPr}}">
                        {{ Str::limit($producto->descripcionPr, 50) }}
                    </span>
                </td>
                <td>{{$producto->compocicionQuimica}}</td>
                <td>{{$producto->consentracionQuimica}}</td>
                <td>{{$producto->fechaFabricacion}}</td>
                <td>
                    @if($diasParaVencer < 0) <span class="badge bg-danger" title="Vencido">
                        <i class="bi bi-exclamation-triangle"></i> {{$producto->fechaVencimiento}}
                        </span>
                        @elseif($diasParaVencer <= 30) <span class="badge bg-warning" title="Por vencer ({{$diasParaVencer}} días)">
                            {{$producto->fechaVencimiento}}
                            </span>
                            @else
                            <span class="badge bg-success">{{$producto->fechaVencimiento}}</span>
                            @endif
                </td>
                <td>{{$producto->unidadMedida}}</td>
                <td>
                    @if($categoria)
                    <span class="badge bg-info">{{$categoria->nombreCat}}</span>
                    @else
                    <span class="badge bg-secondary">Sin categoría</span>
                    @endif
                </td>
                <td class="d-flex gap-2">
                    <a href="/producto/{{$producto->id_producto}}/editar" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form action="/producto/{{$producto->id_producto}}/eliminar" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</section>
</div>
@endsection