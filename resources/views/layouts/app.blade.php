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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
                <a class="navbar-brand font-weight-bold text-primary" href="{{ url('/home') }}">
                    <i class="fas fa-store mr-2"></i>

                    @if(Auth::user()->empresa)
                        {{ Auth::user()->empresa->nombre_empresa }}
                    @else
                        Panel de Control
                    @endif
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainMenu">
                    @auth
                    <ul class="navbar-nav mx-auto">

                        @php
                                // Creamos una variable para saber si es el administrador principal
                                // Puedes ajustarlo si tu administrador tiene otro ID o un campo 'role' == 'admin'
                            $isAdmin = (auth()->user()->rol == 'admin');
                        @endphp

                        @if($isAdmin || auth()->user()->p_productos)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="prodDrop" data-toggle="dropdown">📦 Productos</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('productos.create') }}">➕ Crear Producto</a>
                                <a class="dropdown-item" href="{{ route('productos.index') }}">✏️ Gestionar / Modificar</a>
                            </div>
                        </li>
                        @endif

                        @if($isAdmin || auth()->user()->p_pedidos)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="pedDrop" data-toggle="dropdown">📝 Pedidos</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('ventas.pos') }}">🛒 Crear Pedido (POS)</a>
                                <a class="dropdown-item" href="{{ route('pedidos.gestion') }}">🚫 Modificar o Cancelar</a>
                            </div>
                        </li>
                        @endif

                        @if($isAdmin || auth()->user()->p_cocina)
                        <li class="nav-item"><a class="nav-link" href="{{ route('cocina.index') }}">👨‍🍳 Cocina</a></li>
                        @endif

                        @if($isAdmin || auth()->user()->p_pagos)
                        <li class="nav-item"><a class="nav-link" href="{{ route('pagos.index') }}">💰 Pagos</a></li>
                        @endif

                        @if($isAdmin || auth()->user()->p_informes)
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownInformes" class="nav-link dropdown-toggle font-weight-bold text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-chart-line mr-1 text-primary"></i> Informes
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="navbarDropdownInformes" style="border-radius: 12px;">
                                <a class="dropdown-item py-2" href="{{ route('informes.ventas_diarias') }}">
                                    <i class="fas fa-calendar-day mr-2 text-success"></i> Liquidación Diaria
                                </a>

                                <a class="dropdown-item py-2" href="{{ route('informes.ranking_productos') }}">
                                    <i class="fas fa-hamburger mr-2 text-warning"></i> Ranking Best Sellers
                                </a>

                                <div class="dropdown-divider"></div>


                                <a class="dropdown-item py-2" href="{{ route('informes.comparativo') }}">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    <span>Análisis Mensual</span>
                                </a>
                            </div>
                        </li>
                        @endif

                        @if($isAdmin || auth()->user()->p_usuarios)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                    <i class="fas fa-users-cog text-primary"></i>Usuarios
                                </a>
                                <div class="dropdown-menu">
                                    <a class="fas fa-user-plus" href="{{ route('usuarios.create') }}">Crear Usuario</a>
                                    <a class="fas fa-user-edit" href="{{ route('usuarios.index') }}">Editar Usuarios</a>
                                </div>
                            </li>
                        @endif

                        @if(Auth::user()->rol == 'superadmin')
                            <li class="nav-item">
                               <a class="nav-link" href="{{ route('empresas.create') }}">
                                   <i class="fas fa-plus-circle text-success mr-2"></i> Crear Nueva Empresa
                               </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('empresas.index') }}">
                                    <i class="fas fa-list"></i> Ver Todas las Empresas
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->rol == 'admin' || Auth::user()->rol == 'superadmin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('empresas.edit', Auth::user()->id_empresa) }}">
                                    <i class="fas fa-edit"></i> Configurar Mi Empresa
                                </a>
                            </li>
                        @endif

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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
