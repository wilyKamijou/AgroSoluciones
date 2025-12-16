@extends('home')

@section('contenido')
<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos iguales a ventas */
    .list-group {
        position: absolute;
        z-index: 1000;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }
    .list-group-item {
        cursor: pointer;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .position-relative {
        position: relative;
    }
</style>

<h1 class="text-center mb-4">Módulo de Inventario</h1>

<!-- Mensajes de sesión (igual que antes) -->
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('warning') }}
    @if(session('detalleExistente'))
    <hr>
    <p class="mb-0">
        <strong>Stock actual:</strong> {{ session('detalleExistente')->stock }} unidades
    </p>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-octagon-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="/mAlmacen/guardar" method="POST" id="inventarioForm">
    @csrf

    <div class="card shadow-sm p-4 mb-4">

        <!-- ======================= -->
        <!-- CARD: PRODUCTO -->
        <!-- ======================= -->

        <h4 class="mt-3">Registrar Producto
            <span class="badge bg-secondary float-end" id="productoStatus">Nuevo</span>
        </h4>
        <hr>
        <div class="row g-3">
            <div class="col-md-6 position-relative">
                <label class="form-label">Nombre del Producto *</label>
                <input type="text" id="nombrePr" name="nombrePr" class="form-control" 
                       placeholder="Ingrese el nombre del producto" 
                       autocomplete="off" required>
                <div id="suggestionsProducto" class="list-group position-absolute w-100" style="display:none;"></div>
                <small class="text-muted">Escriba para buscar productos existentes</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nombre Técnico *</label>
                <input type="text" id="nombreTecnico" name="nombreTecnico" class="form-control" 
                       placeholder="Ingrese el nombre técnico" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción *</label>
                <input type="text" id="descripcionPr" name="descripcionPr" class="form-control" 
                       placeholder="Ingrese la descripción" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Composición Química *</label>
                <input type="text" id="compocicionQuimica" name="compocicionQuimica" class="form-control" 
                       placeholder="Ingrese la composición química" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Concentración Química *</label>
                <input type="text" id="consentracionQuimica" name="consentracionQuimica" class="form-control" 
                       placeholder="Ingrese la concentración química" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Fabricación *</label>
                <input type="date" id="fechaFabricacion" name="fechaFabricacion" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de Vencimiento *</label>
                <input type="date" id="fechaVencimiento" name="fechaVencimiento" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Unidad de Medida *</label>
                <input type="text" id="unidadMedida" name="unidadMedida" class="form-control" 
                       placeholder="Ej: kg, litros" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Precio del Producto *</label>
                <input type="text" step="0.01" id="precioPr" name="precioPr" class="form-control" 
                       placeholder="Ingrese el precio" required>
            </div>
            
            <!-- Stock -->
            <div class="col-md-4">
                <label class="form-label">Stock Disponible *</label>
                <input type="number" id="stock" name="stock" class="form-control" 
                       placeholder="Ingrese el stock" min="1" required>
                <small class="text-muted">Si el producto ya existe, se sumará al stock existente</small>
            </div>
            
            <!-- URL de la Imagen -->
            <div class="col-md-4">
                <label class="form-label">URL de la Imagen *</label>
                <div class="input-group">
                    <input type="url" id="imagen_url" name="imagen_url" class="form-control" 
                           placeholder="https://ejemplo.com/imagen.jpg" required>
                    <button type="button" id="previsualizarImagen" class="btn btn-outline-secondary">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <button type="button" class="btn btn-link btn-sm p-0" id="buscarImagenPixabay">
                        <i class="fas fa-search"></i> Buscar en Pixabay
                    </button> |
                    <button type="button" class="btn btn-link btn-sm p-0" id="buscarManualUnsplash">
                        <i class="fas fa-search"></i> Buscar en Unsplash
                    </button>
                </small>
                <div id="imagenPrevisualizacion" class="mt-2 text-center d-none">
                    <img src="" alt="Previsualización" class="img-thumbnail" style="max-height: 150px;">
                    <div id="creditosImagen" class="text-muted small mt-1"></div>
                </div>
            </div>
        </div>

        <!-- ======================= -->
        <!-- CARD: CATEGORÍA -->
        <!-- ======================= -->
        <h4 class="mt-3">Categoría
            <span class="badge bg-secondary float-end" id="categoriaStatus">Nueva</span>
        </h4>
        <hr>
        <div class="row g-3">
            <!-- Nombre -->
            <div class="col-md-6 position-relative">
                <label class="form-label">Nombre de la Categoría *</label>
                <input type="text" id="nombreCat" name="nombreCat" class="form-control" 
                       placeholder="Ingrese el nombre de la categoría" 
                       autocomplete="off" required>
                <div id="suggestionsCategoria" class="list-group position-absolute w-100" style="display:none;"></div>
                <small class="text-muted">Escriba para buscar categorías existentes</small>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <label class="form-label">Descripción *</label>
                <input type="text" id="descripcionCat" name="descripcionCat" class="form-control" 
                       placeholder="Ingrese la descripción" required>
            </div>
        </div>

        <!-- ======================= -->
        <!-- CARD: ALMACÉN -->
        <!-- ======================= -->

        <h4 class="mt-3">Almacén
            <span class="badge bg-secondary float-end" id="almacenStatus">Nuevo</span>
        </h4>
        <hr>
        <div class="row g-3">
            <div class="col-md-4 position-relative">
                <label class="form-label">Nombre del Almacén *</label>
                <input type="text" id="nombreAl" name="nombreAl" class="form-control" 
                       placeholder="Ingrese el nombre del almacén" 
                       autocomplete="off" required>
                <div id="suggestionsAlmacen" class="list-group position-absolute w-100" style="display:none;"></div>
                <small class="text-muted">Escriba para buscar almacenes existentes</small>
            </div>

            <div class="col-md-4">
                <label class="form-label">Descripción *</label>
                <input type="text" id="descripcionAl" name="descripcionAl" class="form-control" 
                       placeholder="Ingrese la descripción del almacén" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Dirección *</label>
                <input type="text" id="direccionAl" name="direccionAl" class="form-control" 
                       placeholder="Ingrese la dirección" required>
            </div>
        </div>

        <!-- Resumen -->
        <div class="alert alert-info mt-4" id="resumenGuardado">
            <h5><i class="bi bi-info-circle me-2"></i>Resumen de la operación:</h5>
            <ul class="mb-0">
                <li>Categoría: <span id="resumenCategoria">Nueva</span></li>
                <li>Almacén: <span id="resumenAlmacen">Nuevo</span></li>
                <li>Producto: <span id="resumenProducto">Nuevo</span></li>
                <li>Stock a agregar: <span id="resumenStock">0</span> unidades</li>
            </ul>
        </div>

        <!-- BOTÓN GLOBAL DEL FORM -->
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-save me-2"></i> Guardar Todo
            </button>
            <button type="button" id="btnLimpiar" class="btn btn-outline-secondary btn-lg ms-2">
                <i class="bi bi-arrow-clockwise me-2"></i> Limpiar
            </button>
        </div>
    </div>
</form>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Variables de estado
    let productoCargado = false;
    let categoriaCargada = false;
    let almacenCargado = false;
    
    // Elementos del DOM - usando los mismos selectores que en ventas
    const nombrePrInput = document.getElementById('nombrePr');
    const nombreCatInput = document.getElementById('nombreCat');
    const nombreAlInput = document.getElementById('nombreAl');
    
    const suggestionsProducto = document.getElementById('suggestionsProducto');
    const suggestionsCategoria = document.getElementById('suggestionsCategoria');
    const suggestionsAlmacen = document.getElementById('suggestionsAlmacen');

    // ====================================
    // 1. AUTOCOMPLETADO PARA PRODUCTOS - IGUAL QUE VENTAS
    // ====================================
    nombrePrInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsProducto.style.display = 'none';
            return;
        }

        // Usar 'q' como parámetro, igual que en ventas
        fetch(`/mAlmacen/buscar-productos?q=${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return res.json();
            })
            .then(data => {
                console.log('Productos encontrados:', data); // Para depuración
                suggestionsProducto.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(producto => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${producto.nombrePr} (${producto.nombreTecnico})`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Cargar todos los datos del producto
                            nombrePrInput.value = producto.nombrePr;
                            document.getElementById('nombreTecnico').value = producto.nombreTecnico || '';
                            document.getElementById('descripcionPr').value = producto.descripcionPr || '';
                            document.getElementById('compocicionQuimica').value = producto.compocicionQuimica || '';
                            document.getElementById('consentracionQuimica').value = producto.consentracionQuimica || '';
                            document.getElementById('fechaFabricacion').value = producto.fechaFabricacion || '';
                            document.getElementById('fechaVencimiento').value = producto.fechaVencimiento || '';
                            document.getElementById('unidadMedida').value = producto.unidadMedida || '';
                            document.getElementById('precioPr').value = producto.precioPr || '';
                            document.getElementById('imagen_url').value = producto.imagen_url || '';
                            
                            // Cargar categoría si existe
                            if (producto.categoria) {
                                document.getElementById('nombreCat').value = producto.categoria.nombreCat || '';
                                document.getElementById('descripcionCat').value = producto.categoria.descripcionCat || '';
                                categoriaCargada = true;
                                document.getElementById('categoriaStatus').textContent = 'Existente';
                                document.getElementById('categoriaStatus').className = 'badge bg-info float-end';
                            }
                            
                            productoCargado = true;
                            document.getElementById('productoStatus').textContent = 'Existente';
                            document.getElementById('productoStatus').className = 'badge bg-info float-end';
                            updateResumen();
                            
                            suggestionsProducto.style.display = 'none';
                            showMessage('success', 'Producto cargado exitosamente');
                        });
                        
                        suggestionsProducto.appendChild(item);
                    });
                    
                    suggestionsProducto.style.display = 'block';
                } else {
                    suggestionsProducto.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar productos:', error);
                suggestionsProducto.style.display = 'none';
                // Mostrar mensaje de error en consola para depuración
                console.log('URL intentada:', `/mAlmacen/buscar-productos?q=${encodeURIComponent(query)}`);
            });
    });

    // ====================================
    // 2. AUTOCOMPLETADO PARA CATEGORÍAS
    // ====================================
    nombreCatInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsCategoria.style.display = 'none';
            return;
        }

        fetch(`/mAlmacen/buscar-categorias?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsCategoria.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(categoria => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${categoria.nombreCat} - ${categoria.descripcionCat}`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            nombreCatInput.value = categoria.nombreCat;
                            document.getElementById('descripcionCat').value = categoria.descripcionCat || '';
                            
                            categoriaCargada = true;
                            document.getElementById('categoriaStatus').textContent = 'Existente';
                            document.getElementById('categoriaStatus').className = 'badge bg-info float-end';
                            updateResumen();
                            
                            suggestionsCategoria.style.display = 'none';
                            showMessage('info', 'Categoría cargada exitosamente');
                        });
                        
                        suggestionsCategoria.appendChild(item);
                    });
                    
                    suggestionsCategoria.style.display = 'block';
                } else {
                    suggestionsCategoria.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar categorías:', error);
                suggestionsCategoria.style.display = 'none';
            });
    });

    // ====================================
    // 3. AUTOCOMPLETADO PARA ALMACENES
    // ====================================
    nombreAlInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 1) {
            suggestionsAlmacen.style.display = 'none';
            return;
        }

        fetch(`/mAlmacen/buscar-almacenes?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsAlmacen.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(almacen => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = `${almacen.nombreAl} - ${almacen.direccionAl}`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            nombreAlInput.value = almacen.nombreAl;
                            document.getElementById('descripcionAl').value = almacen.descripcionAl || '';
                            document.getElementById('direccionAl').value = almacen.direccionAl || '';
                            
                            almacenCargado = true;
                            document.getElementById('almacenStatus').textContent = 'Existente';
                            document.getElementById('almacenStatus').className = 'badge bg-info float-end';
                            updateResumen();
                            
                            suggestionsAlmacen.style.display = 'none';
                            showMessage('info', 'Almacén cargado exitosamente');
                        });
                        
                        suggestionsAlmacen.appendChild(item);
                    });
                    
                    suggestionsAlmacen.style.display = 'block';
                } else {
                    suggestionsAlmacen.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al buscar almacenes:', error);
                suggestionsAlmacen.style.display = 'none';
            });
    });

    // ====================================
    // 4. OCULTAR SUGERENCIAS AL HACER CLIC FUERA - IGUAL QUE VENTAS
    // ====================================
    document.addEventListener('click', function(e) {
        if (!suggestionsProducto.contains(e.target) && e.target !== nombrePrInput) {
            suggestionsProducto.style.display = 'none';
        }
        if (!suggestionsCategoria.contains(e.target) && e.target !== nombreCatInput) {
            suggestionsCategoria.style.display = 'none';
        }
        if (!suggestionsAlmacen.contains(e.target) && e.target !== nombreAlInput) {
            suggestionsAlmacen.style.display = 'none';
        }
    });

    // ====================================
    // 5. FUNCIONES DE IMAGEN (MANTENIDAS)
    // ====================================
    const imagenUrlInput = document.getElementById('imagen_url');
    const previsualizarBtn = document.getElementById('previsualizarImagen');
    const imagenPreview = document.getElementById('imagenPrevisualizacion');
    const previewImg = imagenPreview.querySelector('img');
    const creditosImg = document.getElementById('creditosImagen');
    
    // Buscar en Pixabay
    document.getElementById('buscarImagenPixabay').addEventListener('click', function() {
        let query = nombrePrInput.value.trim();
        if (!query) query = document.getElementById('nombreTecnico').value.trim();
        
        if (!query) {
            showMessage('warning', 'Ingrese primero el nombre del producto');
            return;
        }
        
        window.open(`https://pixabay.com/images/search/${encodeURIComponent(query)}/`, '_blank');
    });
    
    // Buscar en Unsplash
    document.getElementById('buscarManualUnsplash').addEventListener('click', function() {
        window.open('https://unsplash.com/', '_blank');
    });
    
    // Previsualizar imagen
    previsualizarBtn.addEventListener('click', function() {
        const url = imagenUrlInput.value.trim();
        
        if (!url) {
            showMessage('warning', 'Ingrese una URL de imagen');
            return;
        }
        
        // Validar URL
        if (!url.startsWith('http://') && !url.startsWith('https://')) {
            showMessage('error', 'URL inválida. Debe comenzar con http:// o https://');
            return;
        }
        
        previewImg.src = url;
        imagenPreview.classList.remove('d-none');
        
        // Mostrar créditos
        if (url.includes('pixabay.com')) {
            creditosImg.textContent = 'Fuente: Pixabay - Licencia gratuita';
        } else if (url.includes('unsplash.com')) {
            creditosImg.textContent = 'Fuente: Unsplash - Licencia gratuita';
        } else {
            creditosImg.textContent = 'URL personalizada';
        }
        
        showMessage('success', 'Imagen previsualizada');
    });

    // ====================================
    // 6. FUNCIONES AUXILIARES
    // ====================================
    function updateResumen() {
        document.getElementById('resumenCategoria').textContent = categoriaCargada ? 'Existente' : 'Nueva';
        document.getElementById('resumenAlmacen').textContent = almacenCargado ? 'Existente' : 'Nuevo';
        document.getElementById('resumenProducto').textContent = productoCargado ? 'Existente' : 'Nuevo';
        document.getElementById('resumenStock').textContent = document.getElementById('stock').value || '0';
    }
    
    function showMessage(type, text) {
        // Crear mensaje temporal similar al de ventas
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const icon = type === 'success' ? 'bi-check-circle' : 
                    type === 'error' ? 'bi-exclamation-circle' : 
                    type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle';
        
        const message = document.createElement('div');
        message.className = `alert ${alertClass} alert-dismissible fade show`;
        message.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        message.innerHTML = `
            <i class="bi ${icon} me-2"></i>${text}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(message);
        
        // Auto-eliminar después de 3 segundos
        setTimeout(() => {
            if (message.parentNode) {
                message.parentNode.removeChild(message);
            }
        }, 3000);
    }

    // ====================================
    // 7. LIMPIAR FORMULARIO
    // ====================================
    document.getElementById('btnLimpiar').addEventListener('click', function() {
        if (confirm('¿Está seguro de limpiar todo el formulario?')) {
            document.getElementById('inventarioForm').reset();
            productoCargado = categoriaCargada = almacenCargado = false;
            
            document.getElementById('productoStatus').textContent = 'Nuevo';
            document.getElementById('productoStatus').className = 'badge bg-secondary float-end';
            document.getElementById('categoriaStatus').textContent = 'Nueva';
            document.getElementById('categoriaStatus').className = 'badge bg-secondary float-end';
            document.getElementById('almacenStatus').textContent = 'Nuevo';
            document.getElementById('almacenStatus').className = 'badge bg-secondary float-end';
            
            imagenPreview.classList.add('d-none');
            updateResumen();
            
            showMessage('info', 'Formulario limpiado');
        }
    });

    // ====================================
    // 8. ACTUALIZAR RESUMEN AL CAMBIAR STOCK
    // ====================================
    document.getElementById('stock').addEventListener('input', updateResumen);

    // ====================================
    // 9. VALIDACIÓN DEL FORMULARIO
    // ====================================
    document.getElementById('inventarioForm').addEventListener('submit', function(e) {
        const stock = parseInt(document.getElementById('stock').value);
        
        if (isNaN(stock) || stock <= 0) {
            e.preventDefault();
            showMessage('error', 'El stock debe ser un número positivo');
            return;
        }
        
        // Mostrar confirmación
        const confirmMessage = `
¿Confirmar operación?

Categoría: ${categoriaCargada ? 'EXISTENTE' : 'NUEVA'}
Almacén: ${almacenCargado ? 'EXISTENTE' : 'NUEVO'}
Producto: ${productoCargado ? 'EXISTENTE (se sumará stock)' : 'NUEVO'}
Stock: ${stock} unidades

${productoCargado ? 'NOTA: El stock se sumará al existente' : ''}
        `;
        
        if (!confirm(confirmMessage.trim())) {
            e.preventDefault();
        }
    });

    // Inicializar resumen
    updateResumen();
    
    // Mensaje de depuración
    console.log('Script de inventario cargado correctamente');
});
</script>