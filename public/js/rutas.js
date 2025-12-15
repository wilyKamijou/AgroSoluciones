// js/rutas.js - Versión corregida
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownRutas');
    const rutaCheckboxes = document.querySelectorAll('.ruta-checkbox');
    const rutasSeleccionadasContainer = document.getElementById('rutasSeleccionadas');
    const rutasInputHidden = document.getElementById('rutasInputHidden');
    
    // Mapeo de rutas a sus iniciales
    const rutasMap = {
        'Reportes': 'R',
        'Usuarios': 'U', 
        'Clientes': 'C',
        'Empleados': 'E',
        'Ventas': 'V',
        'Almacenes': 'A',
        'Productos': 'P'
    };

    // Hacer la función global
    window.actualizarRutasSeleccionadas = function() {
        const rutasSeleccionadas = [];
        const iniciales = [];
        
        rutaCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const rutaNombre = checkbox.getAttribute('data-nombre');
                rutasSeleccionadas.push(rutaNombre);
                if (rutasMap[rutaNombre]) {
                    iniciales.push(rutasMap[rutaNombre]);
                }
            }
        });
        
        // ORDENAR LAS INICIALES para consistencia
        iniciales.sort();
        
        // Actualizar contenedor de visualización
        rutasSeleccionadasContainer.innerHTML = '';
        if (rutasSeleccionadas.length > 0) {
            // Ordenar también las rutas
            rutasSeleccionadas.sort();
            
            rutasSeleccionadas.forEach(ruta => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2';
                badge.innerHTML = `${ruta} <span class="ms-1" style="cursor:pointer;" onclick="eliminarRuta('${ruta}')">&times;</span>`;
                rutasSeleccionadasContainer.appendChild(badge);
            });
            
            // Mostrar iniciales ordenadas
            rutasInputHidden.value = iniciales.join(' ');
            
            // Actualizar texto del botón
            dropdownButton.innerHTML = `<span>${rutasSeleccionadas.length} ruta(s) seleccionada(s)</span> <span class="dropdown-toggle"></span>`;
            dropdownButton.classList.add('selected');
        } else {
            dropdownButton.innerHTML = '<span>Elige rutas</span> <span class="dropdown-toggle"></span>';
            dropdownButton.classList.remove('selected');
            rutasInputHidden.value = '';
        }
    };
    
    // Función para eliminar ruta desde la badge
    window.eliminarRuta = function(rutaNombre) {
        const checkbox = document.querySelector(`.ruta-checkbox[data-nombre="${rutaNombre}"]`);
        if (checkbox) {
            checkbox.checked = false;
            window.actualizarRutasSeleccionadas(); // Usar window.
        }
    };

    // Event listeners para los checkboxes
    rutaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', window.actualizarRutasSeleccionadas);
    });

    // Mantener dropdown abierto al hacer clic en checkboxes
    const dropdownMenu = document.querySelector('.dropdown-menu');
    if (dropdownMenu) {
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // ===== CARGAR RUTAS EXISTENTES AL EDITAR =====
    // Si hay rutas_acceso guardadas, marcar los checkboxes correspondientes
    const rutasAccesoValue = rutasInputHidden ? rutasInputHidden.value : '';
    
    if (rutasAccesoValue) {
        const iniciales = rutasAccesoValue.trim().split(' ');
        
        // Mapeo inverso más simple
        const mapeoCheckbox = {
            'R': 'ruta-reportes',
            'U': 'ruta-usuarios',
            'C': 'ruta-clientes',
            'E': 'ruta-empleados',
            'V': 'ruta-ventas',
            'A': 'ruta-almacenes',
            'P': 'ruta-productos'
        };
        
        // Marcar checkboxes
        iniciales.forEach(inicial => {
            if (inicial && mapeoCheckbox[inicial]) {
                const checkbox = document.getElementById(mapeoCheckbox[inicial]);
                if (checkbox) {
                    checkbox.checked = true;
                }
            }
        });
        
        // Actualizar visualización después de marcar checkboxes
        setTimeout(() => {
            if (typeof window.actualizarRutasSeleccionadas === 'function') {
                window.actualizarRutasSeleccionadas();
            }
        }, 100);
    }
});