@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  
@stop

@section('content')
    
<div class="container">
    @yield('contenido') 
  </div>
@stop

@section('css')
    
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop