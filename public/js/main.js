// Archivo principal que carga todos los buscadores
document.addEventListener('DOMContentLoaded', function() {
    // Detectar qué página estamos y cargar el buscador correspondiente
    const path = window.location.pathname;
    
    if (path.includes('/cliente')) {
        if (typeof inicializarBuscadorClientes === 'function') {
            inicializarBuscadorClientes();
        }
    } else if (path.includes('/empleado')) {
        if (typeof inicializarBuscadorEmpleados === 'function') {
            inicializarBuscadorEmpleados();
        }
    } else if (path.includes('/venta')) {
        if (typeof inicializarBuscadorVentas === 'function') {
            inicializarBuscadorVentas();
        }
    } else if (path.includes('/tipo')) {
        if (typeof inicializarBuscadorTipos === 'function') {
            inicializarBuscadorTipos();
        }
    } else if (path.includes('/detalleVe')) {
        if (typeof inicializarBuscadorDetallesVenta === 'function') {
            inicializarBuscadorDetallesVenta();
        }
    } else if (path.includes('/almacen')) {
        if (typeof inicializarBuscadorAlmacenes === 'function') {
            inicializarBuscadorAlmacenes();
        }
    } else if (path.includes('/user')) {
        if (typeof inicializarBuscadorUsuarios === 'function') {
            inicializarBuscadorUsuarios();
        }
    }
});