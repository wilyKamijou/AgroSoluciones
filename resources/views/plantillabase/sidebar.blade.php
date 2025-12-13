<aside class="sidebar bg-dark text-white">

    <div class="sidebar-header p-3 text-center">
        <h4 class="mb-0">AgroSoluciones</h4>
        <small>{{ Auth::user()->name }}</small>
    </div>

    <ul class="nav flex-column mt-3">


        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>


        @foreach($menuRutas as $ruta)
        <li class="nav-item">
            <a href="{{ url($ruta->url) }}" class="nav-link text-white">
                <i class="bi bi-chevron-right me-2"></i>
                {{ $ruta->nombreR }}
            </a>
        </li>
        @endforeach

    </ul>

</aside>