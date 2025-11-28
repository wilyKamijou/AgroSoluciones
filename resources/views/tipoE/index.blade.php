@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Tipos de Empleado</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Tipo Empleado (Más compacta) -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Tipo de Empleado</h4>
            
            <form action="/tipo/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Descripción -->
                    <div class="col-md-8">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" placeholder="Ingrese la descripción del tipo de empleado" required>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                     
                    </div>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Lista de Tipos de Empleado</h4>
        
        <div class="d-flex gap-2 align-items-center">
            <!-- Buscador -->
            <div class="input-group" style="width: 300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por descripción...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Contador de resultados -->
    <div class="mb-3">
        <small class="text-muted" id="resultCount">
            Mostrando {{ count($tipos) }} tipos de empleado
        </small>
    </div>

    <div class="table-container-small">
        <table class="table table-hover table-small cols-3" id="tiposTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipos as $tipo)
                <tr class="tipo-row">
                    <td><strong>{{$tipo->id_tipoE}}</strong></td>
                    <td>
                        <span class="fw-bold text-primary">{{$tipo->descripcionTip}}</span>
                    </td>
                    <td class="d-flex gap-2">
                        <a href="/tipo/{{$tipo->id_tipoE}}/editar" 
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        <form action="/tipo/{{$tipo->id_tipoE}}/eliminar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Estás seguro de eliminar este tipo de empleado?')">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const resultCount = document.getElementById('resultCount');
    const tableRows = document.querySelectorAll('#tiposTable .tipo-row');
    const totalTipos = tableRows.length;
    
    function updateSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const descripcion = row.cells[1].textContent.toLowerCase();
            
            // Buscar en ID y descripción
            const match = id.includes(searchTerm) || 
                         descripcion.includes(searchTerm);
            
            if (match || searchTerm === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contador
        if (searchTerm === '') {
            resultCount.textContent = `Mostrando ${totalTipos} tipos de empleado`;
        } else {
            resultCount.textContent = `Encontrados ${visibleCount} de ${totalTipos} tipos de empleado`;
        }
    }
    
    searchInput.addEventListener('input', updateSearch);
    
    // Buscar con Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            updateSearch();
        }
    });
    
    // Focus al buscador al cargar la página
    searchInput.focus();
});
</script>
@endsection