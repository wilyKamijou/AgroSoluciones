function inicializarBuscadorUsuarios() {
    const searchInput = document.getElementById('searchInput');
    const resultCount = document.getElementById('resultCount');
    const tableRows = document.querySelectorAll('#usersTable .user-row');
    const totalUsuarios = tableRows.length;
    
    function updateSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const nombre = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const rol = row.cells[3].textContent.toLowerCase();
            const estado = row.cells[4].textContent.toLowerCase();
            
            // Búsqueda en todos los campos relevantes
            const match = id.includes(searchTerm) || 
                         nombre.includes(searchTerm) || 
                         email.includes(searchTerm) || 
                         rol.includes(searchTerm) || 
                         estado.includes(searchTerm);
            
            if (match || searchTerm === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contador
        if (searchTerm === '') {
            resultCount.textContent = `Mostrando ${totalUsuarios} usuarios`;
        } else {
            resultCount.textContent = `Encontrados ${visibleCount} de ${totalUsuarios} usuarios`;
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

// Auto-ejecutar si estamos en la página de usuarios
if (window.location.pathname.includes('/user')) {
    document.addEventListener('DOMContentLoaded', inicializarBuscadorUsuarios);
}