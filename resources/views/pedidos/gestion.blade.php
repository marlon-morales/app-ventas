@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0" style="border-radius: 20px;">
        <div class="card-header bg-dark text-white p-4">
            <h3 class="mb-0">📋 Gestión de Pedidos Activos</h3>
        </div>
        <div class="card-body p-4">

            <div class="row mb-4 bg-light p-3 rounded shadow-sm mx-1 border">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase">🔢 Código de Pedido</label>
                    <input type="text" id="orderID" class="form-control border-0 shadow-sm" placeholder="Ej: 105">
                </div>
                <div class="col-md-8 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase">👤 Nombre del Cliente</label>
                    <input type="text" id="orderClient" class="form-control border-0 shadow-sm" placeholder="Buscar cliente...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover border">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Detalle del Pedido (Productos)</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                        <tr class="pedido-row border-bottom">
                            <td class="text-center align-middle font-weight-bold col-id" style="font-size: 1.1rem;">
                                <span class="badge badge-dark p-2">#{{ $pedido->id_pedido }}</span>
                            </td>

                            <td class="align-middle col-cliente font-weight-bold text-uppercase" style="color: #444;">
                                {{ $pedido->cliente_nombre }}
                            </td>

                            <td class="col-detalle">
                                @php
                                    $detallesAgrupados = $pedido->detalles->groupBy(function($d) { return $d->producto->categoria; });
                                    $colores = [
                                        'bebidas' => '#e3f2fd',
                                        'asados' => '#fce4ec',
                                        'entradas' => '#fff3e0',
                                        'plato fuerte' => '#f1f8e9',
                                        'default' => '#f8f9fa'
                                    ];
                                @endphp

                                @foreach($detallesAgrupados as $cat => $items)
                                <div class="mb-2 p-2 rounded shadow-sm border-left" style="background-color: {{ $colores[strtolower($cat)] ?? $colores['default'] }}; border-left: 5px solid rgba(0,0,0,0.1);">
                                    <div class="small font-weight-bold text-muted mb-1 text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">
                                        {{ $cat }}
                                    </div>
                                    @foreach($items as $item)
                                    <div class="d-flex align-items-center bg-white rounded-lg px-3 py-2 mb-2 border shadow-sm" style="display: inline-flex !important; margin-right: 10px;">
                                        <span class="font-weight-bold mr-2 text-primary" style="font-size: 1.2rem;">{{ $item->cantidad }}x</span>
                                        <span class="mr-3 font-weight-bold text-dark" style="font-size: 1.1rem;">{{ $item->producto->nombre }}</span>
                                        <img src="{{ asset('uploads/productos/' . $item->producto->imagen) }}"
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </td>

                            <td class="text-center align-middle font-weight-bold text-dark" style="font-size: 1.2rem;">
                                ${{ number_format($pedido->total, 0) }}
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('pedidos.editar', $pedido->id_pedido) }}"
                                       class="btn btn-info btn-sm shadow-sm mr-2 px-3 rounded-pill font-weight-bold">
                                       ✏️ Editar
                                    </a>

                                    <form action="{{ route('pedidos.cancelar', $pedido->id_pedido) }}" method="POST"
                                          onsubmit="return confirm('¿Seguro que desea CANCELAR este pedido?')" style="display:inline;">
                                        {!! csrf_field() !!} <button type="submit" class="btn btn-danger btn-sm shadow-sm px-3 rounded-pill font-weight-bold">
                                            🚫 Cancelar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputID = document.getElementById('orderID');
    const inputClient = document.getElementById('orderClient');

    function filterOrders() {
        const valID = inputID.value.toLowerCase().trim();
        const valClient = inputClient.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.pedido-row');

        rows.forEach(row => {
            const textID = row.querySelector('.col-id').innerText.toLowerCase();
            const textClient = row.querySelector('.col-cliente').innerText.toLowerCase();

            if (textID.includes(valID) && textClient.includes(valClient)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    inputID.addEventListener('keyup', filterOrders);
    inputClient.addEventListener('keyup', filterOrders);
});
</script>
<style>
    .pedido-row:hover {
        background-color: rgba(0,0,0,0.02);
        transition: 0.3s;
    }
    .badge-white {
        background-color: white;
        color: #333;
    }
</style>
@endsection