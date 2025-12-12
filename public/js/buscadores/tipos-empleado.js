// public/js/buscadores/tipos-empleado.js
(function() {
    'use strict';
    
    console.log('Cargando script de búsqueda de tipos de empleado...');
    
    // Verificar si estamos en la vista de tipos de empleado
    const tiposTable = document.getElementById('tiposEmpleadoTable') || document.getElementById('tiposTable');
    
    // Si no hay tabla de tipos, salir
    if (!tiposTable) {
        console.log('No se encontró tabla de tipos de empleado');
        return;
    }
    
    console.log('Tabla de tipos encontrada, inicializando búsqueda...');
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeatures);
    } else {
        initFeatures();
    }
    
    function initFeatures() {
        // Inicializar búsqueda
        initSearch();
    }
    
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = searchInput ? searchInput.nextElementSibling : null;
        const tableRows = tiposTable.querySelectorAll('tbody tr');
        const totalTipos = tableRows.length;
        
        console.log('Elementos encontrados:', {
            searchInput: !!searchInput,
            searchButton: !!searchButton,
            tableRows: tableRows.length
        });
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda');
            return;
        }
        
        // Crear botón de limpiar si no existe
        const inputGroup = searchInput.parentElement;
        if (inputGroup && inputGroup.classList.contains('input-group') && !inputGroup.querySelector('.btn-clear-search')) {
            const clearBtn = document.createElement('button');
            clearBtn.className = 'btn btn-outline-secondary btn-clear-search';
            clearBtn.type = 'button';
            clearBtn.innerHTML = '<i class="bi bi-x"></i>';
            clearBtn.title = 'Limpiar búsqueda';
            clearBtn.style.display = 'none';
            clearBtn.style.marginLeft = '1px';
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
                this.style.display = 'none';
            });
            
            // Insertar después del input
            inputGroup.appendChild(clearBtn);
            
            // Mostrar/ocultar botón según contenido
            searchInput.addEventListener('input', function() {
                clearBtn.style.display = this.value.trim() ? '' : 'none';
            });
        }
        
        // Función principal de búsqueda
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;
            
            console.log('Buscando término:', searchTerm);
            
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let match = false;
                
                // Buscar en todas las celdas (excepto la última de opciones)
                for (let i = 0; i < cells.length - 1; i++) {
                    const cell = cells[i];
                    
                    // Buscar en el texto de la celda
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                        break;
                    }
                }
                
                if (match || searchTerm === '') {
                    row.style.display = '';
                    visibleCount++;
                    
                    // Resaltar texto coincidente (opcional)
                    if (searchTerm !== '') {
                        highlightMatches(row, searchTerm);
                    } else {
                        removeHighlights(row);
                    }
                } else {
                    row.style.display = 'none';
                    removeHighlights(row);
                }
            });
            
            console.log('Tipos encontrados:', visibleCount, 'de', totalTipos);
            
            // Actualizar contador si existe
            const resultCount = document.getElementById('resultCount');
            if (resultCount) {
                if (searchTerm === '') {
                    resultCount.textContent = `Mostrando ${totalTipos} tipos de empleado`;
                } else {
                    resultCount.textContent = `Encontrados ${visibleCount} de ${totalTipos} tipos de empleado`;
                    resultCount.classList.add('text-primary');
                    
                    // Volver al color original después de 2 segundos
                    setTimeout(() => {
                        resultCount.classList.remove('text-primary');
                    }, 2000);
                }
            }
            
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
            let noResultsRow = tiposTable.querySelector('.no-results-row');
            
            if (show) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="4" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-search display-6 d-block mb-2"></i>
                                <h5>No se encontraron tipos de empleado</h5>
                                <p class="mb-0">Intenta con otros términos de búsqueda</p>
                            </div>
                        </td>
                    `;
                    tiposTable.querySelector('tbody').appendChild(noResultsRow);
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
        
        if (searchButton) {
            searchButton.addEventListener('click', function() {
                if (searchInput.value.trim()) {
                    performSearch();
                } else {
                    searchInput.focus();
                }
            });
        }
        
        // Focus al input al cargar la página
        setTimeout(() => {
            if (searchInput.value === '') {
                searchInput.focus();
            }
        }, 300);
        
        // Realizar búsqueda inicial si hay texto en el input
        if (searchInput.value.trim() !== '') {
            performSearch();
        }
        
        console.log('Buscador de tipos de empleado inicializado correctamente');
    }
})();