@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Almacenes</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Almacén -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Almacén</h4>
            
            <form action="/almacen/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Almacén</label>
                        <input type="text" name="nombreAl" class="form-control" placeholder="Ingrese el nombre del almacén" required>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="col-md-4">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcionAl" class="form-control" placeholder="Ingrese la descripción del almacén" required>
                    </div>
                    
                    <!-- Dirección -->
                    <div class="col-md-4">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccionAl" class="form-control" placeholder="Ingrese la dirección del almacén" required>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                    </div>
                </div>
            </form>
        </div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Lista de Ventas</h4>

    <div class="d-flex gap-2">

        <input type="text" class="form-control" placeholder="Buscar por nombre o cliente" style="width: 260px;">

        <a href="{{ url('/almacen/pdf') }}" class="btn btn-danger d-flex align-items-center gap-1 px-3">
            <i class=""></i> PDF
        </a>
    </div>
</div>
           


            <div class="table-container-small">
                <table class="table table-hover table-small cols-5">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Dirección</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($almacens as $almacen)
                        <tr>
                            <td><strong>{{$almacen->id_almacen}}</strong></td>
                            <td>{{$almacen->nombreAl}}</td>
                            <td>
                                <span class="text-muted small">{{$almacen->descripcionAl}}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{$almacen->direccionAl}}</small>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="/almacen/{{$almacen->id_almacen}}/editar" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                
                                <form action="/almacen/{{$almacen->id_almacen}}/eliminar" method="POST">
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