@extends('home')

@section('contenido')

<!-- Cargar CSS personalizado -->
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<section class="content-header">
    <h1 class="text-center mb-4">Gestión de Tipos de Empleado</h1>
</section>

<div class="compact-wrapper">
    <section class="content">
        <!-- Card de Registrar Tipo Empleado (Más compacta) -->
        <div class="card shadow-sm p-4 mb-4 card-compact">
            <h4 class="mb-3">Registrar Nuevo Tipo de Empleado</h4>
            
            <form action="/tipo/guardar" method="POST" class="compact-form">
                @csrf
                
                <div class="row g-3">
                    <!-- Descripción -->
                    <div class="col-md-8">
                        <label class="form-label">Descripción del Tipo</label>
                        <input type="text" name="descripcionTip" class="form-control" placeholder="Ingrese la descripción del tipo de empleado" required>
                    </div>
                    
                    <!-- Botones -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                     
                    </div>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between mb-3">
                <h4>Lista de tipos</h4>

                <input type="text" class="form-control w-25" placeholder="Buscar por nombre o cliente">
            </div>


            <div class="table-container-small">
                <table class="table table-hover table-small cols-3">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipos as $tipo)
                        <tr>
                            <td><strong>{{$tipo->id_tipoE}}</strong></td>
                            <td>{{$tipo->descripcionTip}}</td>
                            <td class="d-flex gap-2">
                                <a href="/tipo/{{$tipo->id_tipoE}}/editar" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                
                                <form action="/tipo/{{$tipo->id_tipoE}}/eliminar" method="POST">
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