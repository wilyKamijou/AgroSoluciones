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
                    <div id="suggestions" class="list-group position-absolute w-100" style="z-index:1000;"></div>
                </div>

                <div class="col-md-6 position-relative">
                    <label class="form-label">Apellidos del Cliente</label>
                    <input type="text" id="apellidosCl" name="apellidosCl" class="form-control" placeholder="Ingrese los apellidos del cliente" autocomplete="off" required>
                    <div id="suggestionsApellidos" class="list-group position-absolute w-100" style="z-index:1000;"></div>
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
                <!-- Producto en Almacén -->
                <div class="col-md-6">
                    <label class="form-label">Producto en Almacén</label>
                    <select name="idDal" id="productoAlmacenSelect" class="form-control" required>
                        <option value="" disabled selected>Seleccione producto</option>
                        @foreach($detalleAs as $detalleA)
                        @if($detalleA->stock > 0) {{-- solo si hay stock --}}
                        @foreach($productos as $producto)
                        @foreach($almacenes as $almacen)
                        @if($detalleA->id_almacen == $almacen->id_almacen && $detalleA->id_producto == $producto->id_producto)
                        <option value="{{ $detalleA->id_producto }}|{{ $detalleA->id_almacen }}" data-precio="{{ $producto->precioPr }}">
                            {{ $producto->nombrePr }} - {{ $producto->unidadMedida }} (Stock: {{ $detalleA->stock }})
                        </option>
                        @endif
                        @endforeach
                        @endforeach
                        @endif
                        @endforeach
                    </select>
                </div>


            </div>

            <!-- ======================= -->
            <!-- DETALLE DE VENTA -->
            <!-- ======================= -->

            <div class="row g-3 mb-3">

                <!-- Precio Unitario -->
                <div class="col-md-4">
                    <label class="form-label">Precio Unitario</label>
                    <input type="text" name="precioDv" id="precioInput" class="form-control" placeholder="Precio se autocompletará">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" id="cantidadDv" name="cantidadDv" class="form-control" required>
                </div>
            </div>

            <!-- ======================= -->
            <!-- BOTÓN GUARDAR -->
            <!-- ======================= -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success btn-lg">Registrar Venta</button>
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

        // Función para autocompletar nombre
        nombreInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 1) {
                suggestionsBox.innerHTML = '';
                return;
            }

            fetch(`/mVenta/buscar?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    data.forEach(cliente => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = cliente.nombreCl;
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            nombreInput.value = cliente.nombreCl;
                            suggestionsBox.innerHTML = '';
                        });
                        suggestionsBox.appendChild(item);
                    });
                });
        });

        // Función para autocompletar apellidos
        apellidosInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 1) {
                suggestionsBoxApellidos.innerHTML = '';
                return;
            }

            fetch(`/mVenta/buscarA?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBoxApellidos.innerHTML = '';
                    data.forEach(cliente => {
                        const item = document.createElement('a');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.href = '#';
                        item.textContent = cliente.apellidosCl;
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            apellidosInput.value = cliente.apellidosCl;
                            suggestionsBoxApellidos.innerHTML = '';
                        });
                        suggestionsBoxApellidos.appendChild(item);
                    });
                });
        });



        /* ================== AUTOCOMPLETAR PRECIO UNITARIO ================== */
        const productoSelect = document.querySelector('select[name="idDal"]');
        const precioInput = document.querySelector('input[name="precioDv"]');

        productoSelect.addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            precioInput.value = selectedOption.dataset.precio || '';
        });


    });
</script>
@endsection