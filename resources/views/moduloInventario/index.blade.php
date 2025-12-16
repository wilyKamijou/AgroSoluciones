@extends('home')

@section('contenido')
<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


<h1 class="text-center mb-4">Modulo de inventario</h1>

<form action="/mAlmacen/guardar" method="POST">
    @csrf

    <div class="card shadow-sm p-4 mb-4">

        <!-- ======================= -->
        <!-- CARD: PRODUCTO -->
        <!-- ======================= -->

        <h4 class="mt-3">Registrar Nuevo Producto</h4>
        <hr>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="nombrePr" class="form-control" placeholder="Ingrese el nombre del producto" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nombre Técnico</label>
                <input type="text" name="nombreTecnico" class="form-control" placeholder="Ingrese el nombre técnico" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcionPr" class="form-control" placeholder="Ingrese la descripción" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Composición Química</label>
                <input type="text" name="compocicionQuimica" class="form-control" placeholder="Ingrese la composición química" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Concentración Química</label>
                <input type="text" name="consentracionQuimica" class="form-control" placeholder="Ingrese la concentración química" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Fabricación</label>
                <input type="date" name="fechaFabricacion" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Vencimiento</label>
                <input type="date" name="fechaVencimiento" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Unidad de Medida</label>
                <input type="text" name="unidadMedida" class="form-control" placeholder="Ej: kg, litros" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Precio del Producto</label>
                <input type="text" step="0.01" name="precioPr" class="form-control" placeholder="Ingrese el precio" required>
            </div>
            <!-- Stock -->
            <div class="col-md-4">
                <label class="form-label">Stock Disponible</label>
                <input type="text" name="stock" class="form-control" placeholder="Ingrese el stock" required>
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


        <!-- ======================= -->
        <!-- CARD: CATEGORÍA -->
        <!-- ======================= -->
        <h4 class="mt-3">Registrar Nueva Categoría</h4>
        <hr>
        <div class="row g-3">
            <!-- Nombre -->
            <div class="col-md-6">
                <label class="form-label">Nombre de la Categoría</label>
                <input type="text" name="nombreCat" class="form-control" placeholder="Ingrese el nombre de la categoría" required>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcionCat" class="form-control" placeholder="Ingrese la descripción" required>
            </div>
        </div>
        <!-- ======================= -->
        <!-- CARD: ALMACÉN -->
        <!-- ======================= -->

        <h4 class="mt-3">Registrar Nuevo Almacén</h4>
        <hr>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nombre del Almacén</label>
                <input type="text" name="nombreAl" class="form-control" placeholder="Ingrese el nombre del almacén" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcionAl" class="form-control" placeholder="Ingrese la descripción del almacén" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccionAl" class="form-control" placeholder="Ingrese la dirección" required>
            </div>
        </div>

        <!-- BOTÓN GLOBAL DEL FORM -->
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-success btn-lg">Guardar Todo</button>
        </div>
    </div>


</form>

@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const inputURL = document.getElementById("imagen_url");
        const previewDiv = document.getElementById("imagenPrevisualizacion");
        const previewImg = previewDiv.querySelector("img");
        const creditos = document.getElementById("creditosImagen");

        /* =============================
           BOTÓN: BUSQUEDA AUTOMÁTICA
           ============================= */
        document.getElementById("buscarImagenPixabay").addEventListener("click", function() {
            if (!inputURL.value.trim()) {
                alert("Debes ingresar primero un nombre de producto para buscar la imagen.");
                return;
            }

            // Simulación de búsqueda automática
            const query = encodeURIComponent(inputURL.value.trim());

            // Abrir búsqueda en Pixabay
            window.open(https://pixabay.com/images/search/${query}, "_blank");
        });

        /* =============================
           BOTÓN: IR A PIXABAY
           ============================= */
        document.getElementById("buscarManualPixabay").addEventListener("click", function() {
            window.open("https://pixabay.com/", "_blank");
        });

        /* =============================
           BOTÓN: IR A UNSPLASH
           ============================= */
        document.getElementById("buscarManualUnsplash").addEventListener("click", function() {
            window.open("https://unsplash.com/", "_blank");
        });

        /* =============================
           BOTÓN: PREVISUALIZAR IMAGEN
           ============================= */
        document.getElementById("previsualizarImagen").addEventListener("click", function() {
            const url = inputURL.value.trim();

            if (!url) {
                alert("Ingrese una URL de imagen primero.");
                return;
            }

            // Mostrar previsualización
            previewImg.src = url;
            previewDiv.classList.remove("d-none");

            // Créditos (automático si proviene de Pixabay)
            if (url.includes("pixabay.com")) {
                creditos.innerHTML = "Fuente: Pixabay · Licencia gratuita";
            } else if (url.includes("unsplash.com")) {
                creditos.innerHTML = "Fuente: Unsplash · Licencia gratuita";
            } else {
                creditos.innerHTML = "";
            }
        });

    });
</script>