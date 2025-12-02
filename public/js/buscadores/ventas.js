function inicializarBuscadorVentas() {
    const searchInput = document.getElementById('searchInput');
    const resultCount = document.getElementById('resultCount');
    const tableRows = document.querySelectorAll('#ventasTable .venta-row');
    const totalVentas = tableRows.length;
    
    function updateSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const fecha = row.cells[1].textContent.toLowerCase();
            const monto = row.cells[2].textContent.toLowerCase();
            const empleado = row.cells[3].textContent.toLowerCase();
            const cliente = row.cells[4].textContent.toLowerCase();
            
            // Búsqueda inteligente en todos los campos
            const match = id.includes(searchTerm) || 
                         fecha.includes(searchTerm) || 
                         monto.includes(searchTerm) || 
                         empleado.includes(searchTerm) || 
                         cliente.includes(searchTerm);
            
            if (match || searchTerm === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contador
        if (searchTerm === '') {
            resultCount.textContent = `Mostrando ${totalVentas} ventas`;
        } else {
            resultCount.textContent = `Encontradas ${visibleCount} de ${totalVentas} ventas`;
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

// Auto-ejecutar si estamos en la página de ventas
if (window.location.pathname.includes('/venta')) {
    document.addEventListener('DOMContentLoaded', inicializarBuscadorVentas);
}