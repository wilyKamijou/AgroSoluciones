function inicializarBuscadorProductos() {
    const searchInput = document.getElementById('searchInput');
    const resultCount = document.getElementById('resultCount');
    const tableRows = document.querySelectorAll('#productosTable .producto-row');
    const totalProductos = tableRows.length;
    
    function updateSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const nombre = row.cells[1].textContent.toLowerCase();
            const nombreTecnico = row.cells[2].textContent.toLowerCase();
            const descripcion = row.cells[3].textContent.toLowerCase();
            const categoria = row.cells[4].textContent.toLowerCase();
            const fechaVencimiento = row.cells[5].textContent.toLowerCase();
            
            // Búsqueda en todos los campos relevantes
            const match = id.includes(searchTerm) || 
                         nombre.includes(searchTerm) || 
                         nombreTecnico.includes(searchTerm) || 
                         descripcion.includes(searchTerm) || 
                         categoria.includes(searchTerm) || 
                         fechaVencimiento.includes(searchTerm);
            
            if (match || searchTerm === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contador
        if (searchTerm === '') {
            resultCount.textContent = `Mostrando ${totalProductos} productos`;
        } else {
            resultCount.textContent = `Encontrados ${visibleCount} de ${totalProductos} productos`;
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
}

// Auto-ejecutar si estamos en la página de productos
if (window.location.pathname.includes('/producto')) {
    document.addEventListener('DOMContentLoaded', inicializarBuscadorProductos);
}