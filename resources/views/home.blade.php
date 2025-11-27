@extends('adminlte::page')

@section('title', 'AgroSoluciones')

@section('content_header')

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