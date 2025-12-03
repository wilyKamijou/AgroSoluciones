// js/buscadores/detalle-venta.js
(function() {
    'use strict';
    
    // Verificar si estamos en la vista de detalles de venta
    const detallesTable = document.getElementById('detallesTable');
    const productoAlmacenSelect = document.getElementById('productoAlmacenSelect');
    
    // Si no hay ninguno de los elementos, salir
    if (!detallesTable && !productoAlmacenSelect) return;
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeatures);
    } else {
        initFeatures();
    }
    
    function initFeatures() {
        // Inicializar búsqueda si hay tabla
        if (detallesTable) {
            initSearch();
        }
        
        // Inicializar autocompletado de precio si hay select
        if (productoAlmacenSelect) {
            initPriceAutocomplete();
        }
    }
    
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.querySelector('.input-group button');
        const resultCount = document.getElementById('resultCount');
        const tableRows = document.querySelectorAll('#detallesTable .detalle-row');
        const totalDetalles = tableRows.length;
        
        if (!searchInput) {
            console.warn('No se encontró el input de búsqueda en la vista de detalles de venta');
            return;
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
                let match = false;
                
                // Buscar en todas las celdas
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                        break;
                    }
                }
                
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
                    resultCount.textContent = `Mostrando ${totalDetalles} detalles de venta`;
                } else {
                    resultCount.textContent = `Encontrados ${visibleCount} de ${totalDetalles} detalles de venta`;
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
    
    function initPriceAutocomplete() {
        // Pasar datos de PHP a JavaScript
        // Esto requiere que en tu vista pases la variable global
        // Puedes hacerlo así en tu vista blade:
        // <script>window.productosPrecios = @json($productosConPrecios);</script>
        
        const precioInput = document.getElementById('precioInput');
        if (!precioInput) return;
        
        // Obtener datos de precios desde variable global
        const productosPrecios = window.productosPrecios || {};
        
        // Evento cuando se selecciona un producto+almacén
        productoAlmacenSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                // Obtener el ID del producto desde el value
                const valueParts = selectedOption.value.split('|');
                const productoId = valueParts[0];
                
                // Buscar el precio en el array de productos
                if (productosPrecios[productoId]) {
                    const precio = productosPrecios[productoId].precio;
                    precioInput.value = parseFloat(precio).toFixed(2);
                } else {
                    // Si no encuentra en el array, intenta obtener del data attribute
                    const precioFromData = selectedOption.getAttribute('data-precio');
                    if (precioFromData) {
                        precioInput.value = parseFloat(precioFromData).toFixed(2);
                    } else {
                        precioInput.value = '';
                        console.warn('No se encontró el precio para este producto');
                    }
                }
            } else {
                precioInput.value = '';
            }
        });
        
        // Limpiar precio cuando se limpia el select
        productoAlmacenSelect.addEventListener('click', function() {
            if (this.value === '') {
                precioInput.value = '';
            }
        });
    }
})();