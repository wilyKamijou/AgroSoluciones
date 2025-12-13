<nav class="navbar navbar-light bg-light px-4">
    <span class="navbar-text">
        Bienvenido, {{ Auth::user()->name }}
    </span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i> Salir
        </button>
    </form>
</nav>