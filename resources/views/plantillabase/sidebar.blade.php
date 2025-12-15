<aside class="sidebar bg-dark text-white">
    <div class="sidebar-header p-3 text-center">
        <!-- Logo y Título -->
        <a href="/" class="brand-link">
            <img src="https://i.ibb.co/6cc0HDTt/Copilot-20251128-140723.png" alt="Admin Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text">
                <b>Agro</b>Soluciones
            </span>
        </a>

        <!-- Nombre de usuario -->
        <div class="user-info mt-2">
            {{ Auth::user()->name }}
        </div>
    </div>

    <ul class="nav flex-column mt-3">


        @foreach($menuRutas as $ruta)
        <li class="nav-item">
            <a href="{{ url($ruta->url) }}" class="nav-link text-white">
                {{ $ruta->nombreR }}
            </a>
        </li>
        @endforeach
    </ul>
</aside>