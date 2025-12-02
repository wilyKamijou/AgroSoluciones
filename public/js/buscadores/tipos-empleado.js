function inicializarBuscadorTipos() {
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
}

// Auto-ejecutar si estamos en la página de tipos de empleado
if (window.location.pathname.includes('/tipo')) {
    document.addEventListener('DOMContentLoaded', inicializarBuscadorTipos);
}