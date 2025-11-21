@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
                    
                    <!-- Botones -->
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card Tabla -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Productos</h4>
                        <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, categoría o descripción...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="productosTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Nombre Técnico</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>F. Vencimiento</th>
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
                            <td>
                                <small class="text-muted">{{$producto->nombreTecnico}}</small>
                            </td>
                            <td>
                                <span class="texto-largo" title="{{$producto->descripcionPr}}">
                                    {{ Str::limit($producto->descripcionPr, 50) }}
                                </span>
                            </td>
                            <td>
                                @if($categoria)
                                    <span class="badge bg-info">{{$categoria->nombreCat}}</span>
                                @else
                                    <span class="badge bg-secondary">Sin categoría</span>
                                @endif
                            </td>
                            <td>
                                @if($diasParaVencer < 0)
                                    <span class="badge bg-danger" title="Vencido">
                                        <i class="bi bi-exclamation-triangle"></i> {{$producto->fechaVencimiento}}
                                    </span>
                                @elseif($diasParaVencer <= 30)
                                    <span class="badge bg-warning" title="Por vencer ({{$diasParaVencer}} días)">
                                        {{$producto->fechaVencimiento}}
                                    </span>
                                @else
                                    <span class="badge bg-success">{{$producto->fechaVencimiento}}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="/producto/{{$producto->id_producto}}/editar" 
                                   class="btn btn-primary btn-sm">
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

<!-- JavaScript para el buscador -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#productosTable .producto-row');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection