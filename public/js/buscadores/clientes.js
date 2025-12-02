// js/buscadores/clientes.js
(function() {
    'use strict';
    
    // Verificar si estamos en la vista de clientes
    const clientesTable = document.getElementById('clientesTable');
    if (!clientesTable) return;
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
    
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.querySelector('.input-group button');
        const tableRows = document.querySelectorAll('#clientesTable .cliente-row');
        const totalClientes = tableRows.length;
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda en la vista de clientes');
            return;
        }
        
        // Crear contador de resultados si no existe
        let resultCount = document.getElementById('resultCount');
        if (!resultCount) {
            const tableContainer = document.querySelector('.table-responsive');
            if (tableContainer) {
                const countDiv = document.createElement('div');
                countDiv.className = 'mb-3';
                const small = document.createElement('small');
                small.className = 'text-muted';
                small.id = 'resultCount';
                countDiv.appendChild(small);
                tableContainer.insertBefore(countDiv, tableContainer.firstChild);
                resultCount = small;
            }
        }
        
        // Crear botón de limpiar
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
                const apellidos = cells[2].textContent.toLowerCase();
                const telefono = cells[3].textContent.toLowerCase();
                
                const match = id.includes(searchTerm) || 
                             nombre.includes(searchTerm) || 
                             apellidos.includes(searchTerm) || 
                             telefono.includes(searchTerm);
                
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
                    resultCount.textContent = `Mostrando ${totalClientes} clientes`;
                } else {
                    resultCount.textContent = `Encontrados ${visibleCount} de ${totalClientes} clientes`;
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
        
        // Inicializar contador
        if (resultCount) {
            resultCount.textContent = `Mostrando ${totalClientes} clientes`;
        }
        
        // Focus al input
        setTimeout(() => {
            searchInput.focus();
        }, 100);
    }
})();