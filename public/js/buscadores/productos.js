// public/js/buscadores/productos.js - VERSIÓN CORREGIDA
(function() {
    'use strict';
    
    console.log('Cargando script de búsqueda de productos...');
    
    // Verificar si estamos en la vista de productos
    const productosTable = document.getElementById('productosTable');
    
    // Si no hay tabla de productos, salir
    if (!productosTable) {
        console.log('No se encontró la tabla de productos');
        return;
    }
    
    console.log('Tabla de productos encontrada, inicializando búsqueda...');
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeatures);
    } else {
        initFeatures();
    }
    
    function initFeatures() {
        // Inicializar búsqueda
        initSearch();
        
        // Inicializar funciones adicionales si existen
        if (typeof initPriceAutocomplete === 'function') {
            initPriceAutocomplete();
        }
    }
    
    function initSearch() {
        // Buscar el input con el ID CORRECTO
        const searchInput = document.getElementById('searchProductos');
        const searchButton = document.getElementById('searchButton');
        const tableRows = document.querySelectorAll('#productosTable tbody tr');
        const totalProductos = tableRows.length;
        
        console.log('Input de búsqueda:', searchInput);
        console.log('Botón de búsqueda:', searchButton);
        console.log('Filas encontradas:', tableRows.length);
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda con ID "searchProductos"');
            return;
        }
        
        // Crear botón de limpiar búsqueda si no existe
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
                    
                    // Para celdas con texto largo, buscar en el texto completo
                    const textoLargo = cell.querySelector('.texto-largo');
                    if (textoLargo && textoLargo.getAttribute('title')) {
                        if (textoLargo.getAttribute('title').toLowerCase().includes(searchTerm)) {
                            match = true;
                            break;
                        }
                    }
                    
                    // Para celdas con badges
                    const badges = cell.querySelectorAll('.badge');
                    if (badges.length > 0) {
                        badges.forEach(badge => {
                            if (badge.textContent.toLowerCase().includes(searchTerm)) {
                                match = true;
                            }
                        });
                        if (match) break;
                    }
                    
                    // Buscar en el texto general de la celda
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                        break;
                    }
                }
                
                // También buscar en el ID
                const idCell = cells[0];
                if (idCell.querySelector('strong') && 
                    idCell.querySelector('strong').textContent.toLowerCase().includes(searchTerm)) {
                    match = true;
                }
                
                if (match || searchTerm === '') {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            console.log('Productos visibles:', visibleCount, 'de', totalProductos);
            
            // Actualizar contador si existe
            const resultCount = document.getElementById('resultCount');
            if (resultCount) {
                if (searchTerm === '') {
                    resultCount.textContent = `(${totalProductos} productos)`;
                } else {
                    resultCount.textContent = `(${visibleCount} de ${totalProductos} productos)`;
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
        
        // Función para mostrar mensaje cuando no hay resultados
        function showNoResultsMessage(show) {
            let noResultsRow = productosTable.querySelector('.no-results-row');
            
            if (show) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="12" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-search display-6 d-block mb-2"></i>
                                <h5>No se encontraron productos</h5>
                                <p class="mb-0">Intenta con otros términos de búsqueda</p>
                            </div>
                        </td>
                    `;
                    productosTable.querySelector('tbody').appendChild(noResultsRow);
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
            searchButton.addEventListener('click', performSearch);
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
        
        console.log('Buscador de productos inicializado correctamente');
    }
})();