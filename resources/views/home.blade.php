@extends('adminlte::page')

@section('title', 'AgroSoluciones')
<link rel="icon" type="image/png" href="https://i.ibb.co/6cc0HDTt/Copilot-20251128-140723.png"> 
@section('content_header')
    <!-- Favicon -->
    
    
    
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
@include('componentes.alerta')
<div class="container">
  @yield('contenido')
</div>
@stop

@section('css')

@stop 

@section('js')
<script>
  console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop