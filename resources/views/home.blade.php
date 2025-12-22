@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card p-4">
                <h3>Bienvenido al Panel de Control</h3>
                <p>Selecciona una opción del menú derecho para comenzar.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div id="menuAcordeon">
                <div class="card mb-2">
                    <div class="card-header bg-white" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left text-dark font-weight-bold" data-toggle="collapse" data-target="#collapseVentas">
                                💰 Gestión de Ventas
                            </button>
                        </h5>
                    </div>
                    <div id="collapseVentas" class="collapse show" data-parent="#menuAcordeon">
                        <div class="card-body">
                            <a href="#" class="d-block mb-2">Nueva Venta</a>
                            <a href="#" class="d-block">Reporte del Día</a>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header bg-white" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left text-dark font-weight-bold collapsed" data-toggle="collapse" data-target="#collapseInventario">
                                📦 Productos
                            </button>
                        </h5>
                    </div>
                    <div id="collapseInventario" class="collapse" data-parent="#menuAcordeon">
                        <div class="card-body">
                            <a href="#" class="d-block mb-2">Lista de Arepas</a>
                            <a href="#" class="d-block">Cargar Stock</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left text-dark font-weight-bold collapsed" data-toggle="collapse" data-target="#collapsePerfil">
                                ⚙️ Configuración
                            </button>
                        </h5>
                    </div>
                    <div id="collapsePerfil" class="collapse" data-parent="#menuAcordeon">
                        <div class="card-body">
                            <a href="#" class="d-block">Perfil de Empresa</a>
                            <a href="#" class="d-block mt-2 text-danger">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection