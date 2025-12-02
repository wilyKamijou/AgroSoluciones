@extends('adminlte::page')

@section('title', 'AgroSoluciones')
<link rel="icon" type="image/png" href="https://i.ibb.co/6cc0HDTt/Copilot-20251128-140723.png"> 

@section('content_header')
    <!-- Favicon -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
@include('componentes.alerta')
<div class="container">
  @yield('contenido')
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/custom.css">  

@stop 

@section('js')
<script>
  console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>

<!-- Cargar solo los scripts necesarios según la vista -->
<script>
  // Detectar automáticamente qué scripts cargar basado en elementos visibles
  document.addEventListener('DOMContentLoaded', function() {
    const scriptsToLoad = [];
    
    if (document.getElementById('clientesTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/clientes.js") }}');
    }
    if (document.getElementById('ventasTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/ventas.js") }}');
    }
    if (document.getElementById('tiposEmpleadoTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/tipos-empleado.js") }}');
    }
    if (document.getElementById('productosTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/productos.js") }}');
    }
    if (document.getElementById('usuariosTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/usuarios.js") }}');
    }
    if (document.getElementById('detallesTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/detalles-venta.js") }}');
    }
    if (document.getElementById('almacenesTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/almacenes.js") }}');
    }
    if (document.getElementById('empleadosTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/empleados.js") }}');
    }
    if (document.getElementById('proveedoresTable')) {
      scriptsToLoad.push('{{ asset("js/buscadores/proveedores.js") }}');
    }
       if (document.getElementById('detallesTable') && window.location.pathname.includes('/detalleAl')) {
      scriptsToLoad.push('{{ asset("js/buscadores/detalle-almacen.js") }}');
    }
    
    // Cargar scripts dinámicamente
    scriptsToLoad.forEach(function(src) {
      const script = document.createElement('script');
      script.src = src;
      script.async = true;
      document.head.appendChild(script);
    });
  });
</script>
@stop