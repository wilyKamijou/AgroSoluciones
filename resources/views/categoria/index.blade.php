@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Categorías</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Categoría -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nueva Categoría</h4>
            
            <form action="/categoria/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Categoría</label>
                        <input type="text" name="nombreCat" class="form-control" placeholder="Ingrese el nombre de la categoría" required>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="col-md-6">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcionCat" class="form-control" placeholder="Ingrese la descripción de la categoría" required>
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
                <h4 class="mb-0">Lista de Categorías</h4>
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o descripción...">
                </div>
            </div>
            
            <div class="table-container-small">
                <table class="table table-hover table-small cols-4" id="categoriasTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr class="categoria-row">
                            <td><strong>{{$categoria->id_categoria}}</strong></td>
                            <td>
                                <span class="fw-bold text-primary">{{$categoria->nombreCat}}</span>
                            </td>
                            <td>
                                <span class="texto-largo" title="{{$categoria->descripcionCat}}">
                                    {{ Str::limit($categoria->descripcionCat, 60) }}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="/categoria/{{$categoria->id_categoria}}/editar" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <form action="/categoria/{{$categoria->id_categoria}}/eliminar" method="POST">
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
    const tableRows = document.querySelectorAll('#categoriasTable .categoria-row');
    
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