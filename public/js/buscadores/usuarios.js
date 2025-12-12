// public/js/buscadores/usuarios.js
(function() {
    'use strict';
    
    console.log('Cargando script de búsqueda de usuarios...');
    
    // Buscar elementos - NOTA: cambiar a 'usuariosTable' si sigues la Opción 1
    const searchInput = document.getElementById('searchUsuarios');
    // Cambiar esto según lo que elijas:
    const usersTable = document.getElementById('usuariosTable') || document.getElementById('usersTable');
    
    if (!searchInput || !usersTable) {
        console.log('Elementos no encontrados:', {
            searchInput: !!searchInput,
            usersTable: !!usersTable,
            searchInputId: 'searchUsuarios',
            tableIds: ['usuariosTable', 'usersTable']
        });
        return;
    }
    
    console.log('Elementos encontrados, inicializando búsqueda...');
    
    const originalRows = Array.from(usersTable.querySelectorAll('tbody tr'));
    const searchButton = searchInput.nextElementSibling;
    
    // Función principal de búsqueda
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        console.log('Buscando:', searchTerm);
        
        if (searchTerm === '') {
            originalRows.forEach(row => {
                row.style.display = '';
                removeHighlights(row);
            });
            return;
        }
        
        let visibleCount = 0;
        
        originalRows.forEach(row => {
            try {
                const cells = row.querySelectorAll('td');
                let match = false;
                
                // ID (columna 0)
                if (cells[0]) {
                    const idElement = cells[0].querySelector('strong');
                    if (idElement && idElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                }
                
                // Nombre y registro (columna 1)
                if (!match && cells[1]) {
                    const nameElement = cells[1].querySelector('.fw-semibold');
                    const registroElement = cells[1].querySelector('small');
                    
                    if (nameElement && nameElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                    if (!match && registroElement && registroElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                }
                
                // Email y último acceso (columna 2)
                if (!match && cells[2]) {
                    const emailElement = cells[2].querySelector('div');
                    const ultimoAccesoElement = cells[2].querySelector('small');
                    
                    if (emailElement && emailElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                    if (!match && ultimoAccesoElement && ultimoAccesoElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                }
                
                // Estado (columna 3)
                if (!match && cells[3]) {
                    const badgeElement = cells[3].querySelector('.badge');
                    if (badgeElement && badgeElement.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                }
                
                if (match) {
                    row.style.display = '';
                    visibleCount++;
                    highlightMatches(row, searchTerm);
                } else {
                    row.style.display = 'none';
                    removeHighlights(row);
                }
            } catch (error) {
                console.error('Error procesando fila:', error);
                row.style.display = '';
            }
        });
        
        console.log('Usuarios encontrados:', visibleCount);
        
        // Mostrar mensaje si no hay resultados
        showNoResultsMessage(visibleCount === 0 && searchTerm !== '');
    }
    
    // Función para resaltar coincidencias
    function highlightMatches(row, searchTerm) {
        const cells = row.querySelectorAll('td');
        const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
        
        cells.forEach((cell, index) => {
            // No resaltar en la columna de opciones
            if (index === cells.length - 1) return;
            
            // Guardar el HTML original si no está guardado
            if (!cell.dataset.originalHtml) {
                cell.dataset.originalHtml = cell.innerHTML;
            }
            
            // Aplicar resaltado
            const newHTML = cell.dataset.originalHtml.replace(regex, '<span class="bg-warning text-dark">$1</span>');
            cell.innerHTML = newHTML;
        });
    }
    
    // Función para quitar resaltados
    function removeHighlights(row) {
        const cells = row.querySelectorAll('td');
        
        cells.forEach(cell => {
            if (cell.dataset.originalHtml) {
                cell.innerHTML = cell.dataset.originalHtml;
            }
            
            const highlightedSpans = cell.querySelectorAll('.bg-warning');
            highlightedSpans.forEach(span => {
                const parent = span.parentNode;
                parent.replaceChild(document.createTextNode(span.textContent), span);
                parent.normalize();
            });
        });
    }
    
    // Función para escapar regex
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    // Función para mostrar mensaje cuando no hay resultados
    function showNoResultsMessage(show) {
        let noResultsRow = usersTable.querySelector('.no-results-row');
        
        if (show) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-search display-6 d-block mb-2"></i>
                            <h5>No se encontraron usuarios</h5>
                            <p class="mb-0">Intenta con otros términos de búsqueda</p>
                        </div>
                    </td>
                `;
                usersTable.querySelector('tbody').appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
    
    // Event Listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(performSearch, 300);
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    
    // Limpiar con Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            performSearch();
            this.focus();
        }
    });
    
    // Botón de búsqueda
    if (searchButton && searchButton.tagName === 'BUTTON') {
        searchButton.addEventListener('click', performSearch);
        
        // Convertir en botón de limpiar cuando hay texto
        searchInput.addEventListener('input', function() {
            if (this.value.trim()) {
                searchButton.innerHTML = '<i class="bi bi-x"></i>';
                searchButton.title = 'Limpiar búsqueda';
                searchButton.onclick = function() {
                    searchInput.value = '';
                    performSearch();
                    searchInput.focus();
                };
            } else {
                searchButton.innerHTML = '<i class="bi bi-search"></i>';
                searchButton.title = 'Buscar';
                searchButton.onclick = performSearch;
            }
        });
    }
    
    // Inicializar
    console.log('Buscador de usuarios inicializado');
    
})();