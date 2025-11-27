@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Detalles de Ventas</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Detalle Venta -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Detalle de Venta</h4>
            
            <form action="/detalleVe/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Venta -->
                    <div class="col-md-6">
                        <label class="form-label">Venta</label>
                        <select name="id_venta" class="form-control" required>
                            <option value="" disabled selected>Seleccione la venta</option>
                            @foreach ($ventas as $venta)
                                @foreach ($clientes as $cliente)
                                    @if($venta->id_cliente == $cliente->id_cliente)
                                        <option value="{{$venta->id_venta}}">
                                            {{$venta->id_venta}} - {{$cliente->nombreCl}} - {{$venta->fechaVe}}
                                        </option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Almacén y Producto -->
                    <div class="col-md-6">
                        <label class="form-label">Producto en Almacén</label>
                        <select name="idDal" class="form-control" required>
                            <option value="" disabled selected>Seleccione producto y almacén</option>
                            @foreach($detalleAs as $detalleA)
                                @foreach($productos as $producto)
                                    @foreach($almacenes as $almacen)
                                        @if (($detalleA->id_almacen == $almacen->id_almacen) and ($detalleA->id_producto == $producto->id_producto))
                                            <option value="{{$detalleA->id_producto}}|{{$detalleA->id_almacen}}">
                                                {{$producto->nombrePr}} - {{$almacen->nombreAl}}
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Precio -->
                    <div class="col-md-4">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" name="precioDv" class="form-control" placeholder="Ingrese el precio" required>
                    </div>
                    
                    <!-- Cantidad -->
                    <div class="col-md-4">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidadDv" class="form-control" placeholder="Ingrese la cantidad" required>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Lista de Ventas</h4>

    <div class="d-flex gap-2">

        <input type="text" class="form-control" placeholder="Buscar por nombre o cliente" style="width: 260px;">

        <a href="{{ url('/detalleVe/pdf') }}" class="btn btn-danger d-flex align-items-center gap-1 px-3">
            <i class=""></i> PDF
        </a>
    </div>
</div>
            </div>

            <div class="table-container-small">
                <table class="table table-hover table-small cols-4">
                    <thead class="table-light">
                        <tr>
                            <th>ID Detalle</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalleVs as $detalleV)
                        <tr>
                            <td>
                                <small class="text-muted">Venta: {{$detalleV->id_venta}}</small><br>
                                <small class="text-muted">Producto: {{$detalleV->id_producto}}</small><br>
                                <small class="text-muted">Almacén: {{$detalleV->id_almacen}}</small>
                            </td>
                            <td>
                                <strong>${{number_format($detalleV->precioDv, 2)}}</strong>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{$detalleV->cantidadDv}}</span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                
                                <form action="/detalleVe/{{$detalleV->id_venta}}/{{$detalleV->id_producto}}/{{$detalleV->id_almacen}}/eliminar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection