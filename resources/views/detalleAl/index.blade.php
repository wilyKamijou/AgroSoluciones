@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Detalles de Almacenes</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Detalle Almacén -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Detalle de Almacén</h4>
            
            <form action="/detalleAl/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Producto -->
                    <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        <select name="id_producto" class="form-control" required>
                            <option value="" disabled selected>Seleccione el producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{$producto->id_producto}}">{{$producto->nombrePr}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Almacén -->
                    <div class="col-md-4">
                        <label class="form-label">Almacén</label>
                        <select name="id_almacen" class="form-control" required>
                            <option value="" disabled selected>Seleccione el almacén</option>
                            @foreach ($almacens as $almacen)
                                <option value="{{$almacen->id_almacen}}">{{$almacen->nombreAl}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Stock -->
                    <div class="col-md-4">
                        <label class="form-label">Stock Disponible</label>
                        <input type="number" name="stock" class="form-control" placeholder="Ingrese el stock" required>
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
                <h4 class="mb-0">Lista de Detalles de Almacenes</h4>
                 <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar por producto, almacén o categoría...">
                </div>
            </div>

    
            <div class="table-container-small">
                <table class="table table-hover table-small cols-6" id="detallesTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Almacén</th>
                            <th>Stock</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $detalle)
                        @php
                            $producto = $productos->firstWhere('id_producto', $detalle->id_producto);
                            $almacen = $almacens->firstWhere('id_almacen', $detalle->id_almacen);
                        @endphp
                        <tr class="detalle-row">
                            <td>
                                <small class="text-muted">{{$detalle->id_producto}}-{{$detalle->id_almacen}}</small>
                            </td>
                            <td>
                                @if($producto)
                                    {{$producto->nombrePr}}
                                @else
                                    <span class="text-danger">Producto no encontrado</span>
                                @endif
                            </td>
                            <td>
                                @if($producto)
                                    <span class="badge bg-secondary">{{$producto->id_categoria}}</span>
                                @else
                                    <span class="text-danger">-</span>
                                @endif
                            </td>
                            <td>
                                @if($almacen)
                                    {{$almacen->nombreAl}}
                                @else
                                    <span class="text-danger">Almacén no encontrado</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge @if($detalle->stock > 10) bg-success @elseif($detalle->stock > 0) bg-warning @else bg-danger @endif">
                                    {{$detalle->stock}}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="/detalleAl/{{$detalle->id_producto}}/{{$detalle->id_almacen}}/editar" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <form action="/detalleAl/{{$detalle->id_producto}}/{{$detalle->id_almacen}}/eliminar" method="POST">
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
    const tableRows = document.querySelectorAll('#detallesTable .detalle-row');
    
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