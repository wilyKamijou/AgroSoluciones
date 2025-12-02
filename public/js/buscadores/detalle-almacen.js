// js/buscadores/detalle-almacen.js
(function() {
    'use strict';
    
    // Verificar si estamos en la vista de detalle almacén
    const detallesTable = document.getElementById('detallesTable');
    if (!detallesTable) return;
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
    
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.querySelector('button.btn-outline-secondary');
        const tableRows = document.querySelectorAll('#detallesTable .detalle-row');
        const totalDetalles = tableRows.length;
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda en la vista de detalles de almacén');
            return;
        }
        
        // Función principal de búsqueda - MÁS SIMPLE
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;
            
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                
                if (rowText.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Actualizar contador si existe
            const resultCount = document.getElementById('resultCount');
            if (resultCount) {
                if (searchTerm === '') {
                    resultCount.textContent = `Mostrando ${totalDetalles} detalles de almacén`;
                } else {
                    resultCount.textContent = `Encontrados ${visibleCount} de ${totalDetalles} detalles de almacén`;
                }
            }
        }
        
        // Event Listeners simples
        searchInput.addEventListener('input', performSearch);
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
        
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        // Focus al input
        setTimeout(() => {
            searchInput.focus();
        }, 100);
    }
})();