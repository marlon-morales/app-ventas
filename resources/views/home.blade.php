@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <h2 class="text-primary">Panel de Control - {{ Auth::user()->name }}</h2>
                    <p>Gestiona las ventas de tu empresa hoy.</p>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="accordion" id="menuPrincipal">

                <div class="card mb-2 shadow-sm">
                    <div class="card-header bg-dark" id="h-admin">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left text-white font-weight-bold" data-toggle="collapse" data-target="#c-admin">
                                ⚙️ Configuración Menú
                            </button>
                        </h5>
                    </div>
                    <div id="c-admin" class="collapse show" data-parent="#menuPrincipal">
                        <div class="card-body">
                            <a href="{{ route('productos.create') }}" class="btn btn-warning btn-block font-weight-bold">
                                ＋ Nuevo Producto
                            </a>
                            <hr>
                            <a href="#" class="btn btn-outline-secondary btn-sm btn-block">Ver Inventario</a>
                        </div>
                    </div>
                </div>

                <div class="card mb-2 shadow-sm">
                    <div class="card-header bg-primary" id="h-ventas">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left text-white font-weight-bold collapsed" data-toggle="collapse" data-target="#c-ventas" >
                                🛒 Punto de Venta
                            </button>
                        </h5>
                    </div>
                    <div id="c-ventas" class="collapse" data-parent="#menuPrincipal">
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="{{ route('ventas.pos') }}" class="btn btn-link text-primary font-weight-bold">
                                        🛍️ Crear Nueva Venta
                                    </a>
                                </li>
                                <li class="list-group-item"><a href="#">Pedidos en Cocina</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection