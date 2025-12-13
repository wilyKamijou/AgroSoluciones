<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="{{ asset('css/plantillabase.css') }}">
</head>

<body>

    <div class="d-flex">


        @include('plantillabase.sidebar')


        <div class="flex-grow-1 main-content">


            @include('plantillabase.navbar')

            <main class="p-4">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/panel.js') }}"></script>
</body>

</html>