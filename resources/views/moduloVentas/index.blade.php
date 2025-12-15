@extends('home')

@section('contenido')
<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Modulo de Ventas</h1>
</section>

<div class="container">
    <div class="card shadow-sm p-4 mb-4">
        <h4 class="mb-3">Registrar Venta Completa</h4>

        <form action="/mVenta/guardar" method="POST" id="ventaForm">
            @csrf

            <!-- ======================= -->
            <!-- CLIENTE -->
            <!-- ======================= -->
            <div class="row g-3 mb-3">
                <div class="col-md-6 position-relative">
                    <label class="form-label">Nombre del Cliente</label>
                    <input type="text" id="nombreCl" name="nombreCl" class="form-control" placeholder="Ingrese nombre del cliente" autocomplete="off" required>
                    <div id="suggestions" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></div>
                </div>

                <div class="col-md-6 position-relative">
                    <label class="form-label">Apellidos del Cliente</label>
                    <input type="text" id="apellidosCl" name="apellidosCl" class="form-control" placeholder="Ingrese los apellidos del cliente" autocomplete="off" required>
                    <div id="suggestionsApellidos" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" id="telefonoCl" name="telefonoCl" class="form-control" placeholder="Teléfono del cliente">
                </div>
            </div>

            <!-- ======================= -->
            <!-- EMPLEADO -->
            <!-- ======================= -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Empleado</label>
                    <select name="id_empleado" class="form-control" required>
                        <option value="" disabled selected>Seleccione un empleado</option>
                        @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id_empleado }}">
                            {{ $empleado->nombreEm }} {{ $empleado->apellidosEm }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ======================= -->
            <!-- PRODUCTOS -->
            <!-- ======================= -->
            <h5 class="mt-4 mb-3">Productos a Vender</h5>
            
            <div id="productos-container">
                <div class="row g-3 mb-3 producto-row" data-index="0">
                    <div class="col-md-4">
                        <label class="form-label">Producto en Almacén</label>
                        <select name="productos[0][idDal]" class="form-control producto-select" required>
                            <option value="" disabled selected>Seleccione producto</option>
                            @foreach($detalleAs as $detalleA)
                                @if($detalleA->stock > 0)
                                    @foreach($productos as $producto)
                                        @foreach($almacenes as $almacen)
                                            @if($detalleA->id_almacen == $almacen->id_almacen && $detalleA->id_producto == $producto->id_producto)
                                                <option value="{{ $detalleA->id_producto }}|{{ $detalleA->id_almacen }}" 
                                                        data-precio="{{ $producto->precioPr }}"
                                                        data-stock="{{ $detalleA->stock }}">
                                                    {{ $producto->nombrePr }} - {{ $producto->unidadMedida }} 
                                                    (Stock: {{ $detalleA->stock }})
                                                </option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="productos[0][cantidadDv]" class="form-control cantidad-input" 
                               min="1" value="1" required>
                        <small class="text-muted stock-info"></small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" name="productos[0][precioDv]" class="form-control precio-input" value="" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control subtotal-input" readonly>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-product" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Agregar Producto
            </button>

            <!-- ======================= -->
            <!-- RESUMEN -->
            <!-- ======================= -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Resumen de Venta</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Productos:</span>
                                <span id="total-productos">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Monto Total:</span>
                                <span id="monto-total">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================= -->
            <!-- BOTÓN GUARDAR -->
            <!-- ======================= -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Registrar Venta Completa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nombreInput = document.getElementById('nombreCl');
        const apellidosInput = document.getElementById('apellidosCl');
        const telefonoInput = document.getElementById('telefonoCl');
        const suggestionsBox = document.getElementById('suggestions');
        const suggestionsBoxApellidos = document.getElementById('suggestionsApellidos');
        const container = document.getElementById('productos-container');
        const addButton = document.getElementById('add-product');
        
        // Almacenar productos seleccionados
        let selectedProducts = new Set();
        let productIndex = 1;

        // Función para actualizar las opciones de productos
        function updateProductOptions() {
            const allOptions = document.querySelectorAll('.producto-select option');
            
            // Reactivar todas las opciones primero
            allOptions.forEach(option => {
                option.disabled = false;
                option.style.display = '';
            });
            
            // Desactivar opciones ya seleccionadas en otros selects
            document.querySelectorAll('.producto-select').forEach(select => {
                const selectedValue = select.value;
                if (selectedValue) {
                    // Desactivar esta opción en todos los selects excepto en el actual
                    allOptions.forEach(option => {
                        if (option.value === selectedValue && option.parentElement !== select) {
                            option.disabled = true;
                            option.style.display = 'none';
                        }
                    });
                }
            });
        }

        // Función para autocompletar nombre
        nombreInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 1) {
                suggestionsBox.style.display = 'none';
                return;
            }

            fetch(`/mVenta/buscar?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(cliente => {
                            const item = document.createElement('a');
                            item.classList.add('list-group-item', 'list-group-item-action');
                            item.href = '#';
                            item.textContent = `${cliente.nombreCl} ${cliente.apellidosCl}`;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                nombreInput.value = cliente.nombreCl;
                                apellidosInput.value = cliente.apellidosCl;
                                telefonoInput.value = cliente.telefonoCl || '';
                                suggestionsBox.style.display = 'none';
                                suggestionsBoxApellidos.style.display = 'none';
                            });
                            suggestionsBox.appendChild(item);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                });
        });

        // Función para autocompletar apellidos
        apellidosInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 1) {
                suggestionsBoxApellidos.style.display = 'none';
                return;
            }

            fetch(`/mVenta/buscarA?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBoxApellidos.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(cliente => {
                            const item = document.createElement('a');
                            item.classList.add('list-group-item', 'list-group-item-action');
                            item.href = '#';
                            item.textContent = `${cliente.nombreCl} ${cliente.apellidosCl}`;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                nombreInput.value = cliente.nombreCl;
                                apellidosInput.value = cliente.apellidosCl;
                                telefonoInput.value = cliente.telefonoCl || '';
                                suggestionsBoxApellidos.style.display = 'none';
                                suggestionsBox.style.display = 'none';
                            });
                            suggestionsBoxApellidos.appendChild(item);
                        });
                        suggestionsBoxApellidos.style.display = 'block';
                    } else {
                        suggestionsBoxApellidos.style.display = 'none';
                    }
                });
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!suggestionsBox.contains(e.target) && e.target !== nombreInput) {
                suggestionsBox.style.display = 'none';
            }
            if (!suggestionsBoxApellidos.contains(e.target) && e.target !== apellidosInput) {
                suggestionsBoxApellidos.style.display = 'none';
            }
        });

        // Agregar nueva fila de producto
        addButton.addEventListener('click', function() {
            const newRow = container.querySelector('.producto-row').cloneNode(true);
            const index = productIndex;
            
            // Actualizar atributos
            newRow.setAttribute('data-index', index);
            
            // Limpiar valores
            newRow.querySelector('.producto-select').value = '';
            newRow.querySelector('.cantidad-input').value = 1;
            newRow.querySelector('.precio-input').value = '';
            newRow.querySelector('.subtotal-input').value = '';
            newRow.querySelector('.stock-info').textContent = '';
            
            // Actualizar nombres de inputs
            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            });
            
            container.appendChild(newRow);
            productIndex++;
            
            // Adjuntar eventos
            attachProductEvents(newRow);
            updateProductOptions();
            updateSummary();
        });

        // Función para adjuntar eventos a una fila de producto
        function attachProductEvents(row) {
            const select = row.querySelector('.producto-select');
            const cantidadInput = row.querySelector('.cantidad-input');
            const precioInput = row.querySelector('.precio-input');
            const subtotalInput = row.querySelector('.subtotal-input');
            const stockInfo = row.querySelector('.stock-info');
            const removeBtn = row.querySelector('.remove-product');
            
            // Evento para cambiar producto
            select.addEventListener('change', function() {
                const selectedOption = this.selectedOptions[0];
                const precio = selectedOption.dataset.precio || '';
                const stock = selectedOption.dataset.stock || 0;
                
                // Autorellenar precio
                precioInput.value = precio;
                
                // Mostrar información de stock
                stockInfo.textContent = `Stock: ${stock}`;
                
                // Actualizar opciones en todos los selects
                updateProductOptions();
                
                // Calcular subtotal
                calcularSubtotal(row);
                updateSummary();
                
                // Validar cantidad
                cantidadInput.max = stock;
                if (parseInt(cantidadInput.value) > parseInt(stock)) {
                    cantidadInput.value = stock;
                    calcularSubtotal(row);
                    updateSummary();
                }
            });
            
            // Evento para cambiar cantidad
            cantidadInput.addEventListener('input', function() {
                const stock = parseInt(select.selectedOptions[0]?.dataset.stock || 0);
                const cantidad = parseInt(this.value);
                
                if (cantidad > stock) {
                    this.value = stock;
                    alert(`No hay suficiente stock. Máximo disponible: ${stock}`);
                }
                
                calcularSubtotal(row);
                updateSummary();
            });
            
            // Evento para cambiar precio
            precioInput.addEventListener('input', function() {
                calcularSubtotal(row);
                updateSummary();
            });
            
            // Evento para eliminar fila
            removeBtn.addEventListener('click', function() {
                if (container.querySelectorAll('.producto-row').length > 1) {
                    row.remove();
                    updateProductOptions();
                    updateSummary();
                }
            });
        }

        // Función para calcular subtotal
        function calcularSubtotal(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
            const subtotal = cantidad * precio;
            
            row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
        }

        // Función para actualizar el resumen
        function updateSummary() {
            const filas = document.querySelectorAll('.producto-row');
            let totalProductos = 0;
            let montoTotal = 0;
            
            filas.forEach(fila => {
                const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
                const subtotal = parseFloat(fila.querySelector('.subtotal-input').value) || 0;
                
                if (cantidad > 0 && subtotal > 0) {
                    totalProductos += cantidad;
                    montoTotal += subtotal;
                }
            });
            
            document.getElementById('total-productos').textContent = totalProductos;
            document.getElementById('monto-total').textContent = `$${montoTotal.toFixed(2)}`;
        }

        // Inicializar eventos para la primera fila
        attachProductEvents(container.querySelector('.producto-row'));
        
        // Validar formulario antes de enviar
        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            const filas = document.querySelectorAll('.producto-row');
            let hasValidProducts = false;
            
            filas.forEach(fila => {
                const select = fila.querySelector('.producto-select');
                const cantidad = fila.querySelector('.cantidad-input').value;
                const precio = fila.querySelector('.precio-input').value;
                
                if (select.value && cantidad && precio) {
                    hasValidProducts = true;
                }
            });
            
            if (!hasValidProducts) {
                e.preventDefault();
                alert('Debe agregar al menos un producto válido.');
                return;
            }
            
            // Verificar si hay productos duplicados
            const selectedValues = [];
            let hasDuplicates = false;
            
            document.querySelectorAll('.producto-select').forEach(select => {
                if (select.value) {
                    if (selectedValues.includes(select.value)) {
                        hasDuplicates = true;
                    } else {
                        selectedValues.push(select.value);
                    }
                }
            });
            
            if (hasDuplicates) {
                e.preventDefault();
                alert('No puede seleccionar el mismo producto más de una vez.');
                return;
            }
        });
    });
</script>
@endsection