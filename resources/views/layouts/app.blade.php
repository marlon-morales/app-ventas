<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .navbar { background-color: #1a1a1a !important; padding: 0.8rem 2rem; }
        .nav-link { font-weight: 500; color: rgba(255,255,255,0.8) !important; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover { color: #ff9f43 !important; transform: translateY(-2px); }
        .dropdown-menu { border: none; shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; padding: 10px; }
        .dropdown-item { border-radius: 8px; padding: 8px 20px; font-size: 0.9rem; transition: 0.2s; }
        .dropdown-item:hover { background-color: #ff9f43; color: white; }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; color: #ff9f43 !important; }
        .active-link { border-bottom: 2px solid #ff9f43; color: white !important; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    🏢 {{ Auth::check() ? (Auth::user()->name_empresa ?? 'Mi Negocio') : config('app.name') }}
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainMenu">
                    @auth
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="prodDrop" data-toggle="dropdown">📦 Productos</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('productos.create') }}">➕ Crear Producto</a>
                                <a class="dropdown-item" href="{{ route('productos.index') }}">✏️ Gestionar / Modificar</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="pedDrop" data-toggle="dropdown">📝 Pedidos</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('ventas.pos') }}">🛒 Crear Pedido (POS)</a>
                                <a class="dropdown-item" href="#">🚫 Modificar o Cancelar</a>
                            </div>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="{{ route('cocina.index') }}">👨‍🍳 Cocina</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pagos.index') }}">💰 Pagos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">📊 Informes</a></li>
                    </ul>
                    @endauth

                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown">
                                    👤 {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar Sesión
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
