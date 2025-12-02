// js/buscadores/almacenes.js
(function() {
    'use strict';
    
    // Verificar si estamos en la vista de almacenes
    const almacenesTable = document.getElementById('almacenesTable');
    if (!almacenesTable) return;
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
    
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.querySelector('.input-group button');
        const resultCount = document.getElementById('resultCount');
        const tableRows = document.querySelectorAll('#almacenesTable .almacen-row');
        const totalAlmacenes = tableRows.length;
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda en la vista de almacenes');
            return;
        }
        
        // Crear botón de limpiar si no existe
        const inputGroup = document.querySelector('.input-group');
        if (inputGroup && !inputGroup.querySelector('.btn-clear-search')) {
            const clearBtn = document.createElement('button');
            clearBtn.className = 'btn btn-outline-secondary btn-clear-search';
            clearBtn.type = 'button';
            clearBtn.innerHTML = '<i class="bi bi-x"></i>';
            clearBtn.title = 'Limpiar búsqueda';
            inputGroup.appendChild(clearBtn);
            
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });
        }
        
        // Función principal de búsqueda
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;
            
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 4) return;
                
                const id = cells[0].textContent.toLowerCase();
                const nombre = cells[1].textContent.toLowerCase();
                const descripcion = cells[2].textContent.toLowerCase();
                const direccion = cells[3].textContent.toLowerCase();
                
                const match = id.includes(searchTerm) || 
                             nombre.includes(searchTerm) || 
                             descripcion.includes(searchTerm) || 
                             direccion.includes(searchTerm);
                
                if (match || searchTerm === '') {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Actualizar contador
            if (resultCount) {
                if (searchTerm === '') {
                    resultCount.textContent = `Mostrando ${totalAlmacenes} almacenes`;
                } else {
                    resultCount.textContent = `Encontrados ${visibleCount} de ${totalAlmacenes} almacenes`;
                }
            }
        }
        
        // Event Listeners
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