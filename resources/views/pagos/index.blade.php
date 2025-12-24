@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 bg-white" style="min-height: 100vh;">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="letter-spacing: -1px;">💰 Módulo de Recaudación</h2>
        <p class="text-muted small text-uppercase">Finalice los pedidos despachados por cocina</p>
    </div>

    <div class="row px-2">
        @forelse($pedidos as $pedido)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 20px; background: #fdfdfd;">

                <div class="card-header bg-dark text-white text-center py-3" style="border-radius: 20px 20px 0 0;">
                    <h6 class="m-0 font-weight-bold">{{ $pedido->cliente_nombre }}</h6>
                    <small class="text-warning">Ticket #{{ $pedido->id_pedido }}</small>
                </div>

                <div class="card-body p-2">
                    @php
                        $detallesAgrupados = $pedido->detalles->groupBy(function($d) {
                            return $d->producto->categoria;
                        });

                        $colores = [
                            'bebidas' => '#e3f2fd',
                            'plato fuerte' => '#f1f8e9',
                            'entradas' => '#fff3e0',
                            'asados' => '#fce4ec',
                            'default' => '#f8f9fa'
                        ];
                    @endphp

                    @foreach($detallesAgrupados as $categoria => $items)
                        @php $bg = $colores[strtolower($categoria)] ?? $colores['default']; @endphp

                        <div class="mb-2 p-2 rounded-lg" style="background-color: {{ $bg }};">
                            <div class="text-center font-weight-bold text-uppercase mb-3 text-secondary" style="font-size: 0.85rem; letter-spacing: 2px; opacity: 0.8;">
                                {!! $categoria !!}
                            </div>

                            @foreach($items as $item)
                            <div class="d-flex align-items-center justify-content-center mb-2 bg-white rounded-lg p-2 shadow-sm mx-auto" style="max-width: 98%; border: 1px solid rgba(0,0,0,0.05);">

                                <img src="{{ asset('uploads/productos/' . $item->producto->imagen) }}"
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 10px;" class="mr-3">

                                <div class="text-center" style="line-height: 1.2;">
                                    <span class="d-block text-dark font-weight-bold" style="font-size: 1.05rem;">
                                        {{ $item->cantidad }}x {{ $item->producto->nombre }}
                                    </span>
                                    <span class="text-success font-weight-bold" style="font-size: 0.95rem;">
                                        ${{ number_format($item->cantidad * $item->precio_unitario, 0) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="text-center mt-3 pt-3 border-top">
                        <div class="mb-3">
                            <span class="text-muted d-block font-weight-bold" style="font-size: 0.9rem;">TOTAL DE LA CUENTA</span>
                            <span class="h2 font-weight-bold text-primary" style="letter-spacing: -1px;">
                                ${{ number_format($pedido->total, 0) }}
                            </span>
                        </div>

                        <form action="{{ route('pagos.finalizar', $pedido->id_pedido) }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-block rounded-pill font-weight-bold py-2 shadow-sm mb-2">
                                💳 COBRAR AHORA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="display-1 text-muted opacity-25">📤</div>
            <p class="text-muted">No hay cuentas pendientes por cobrar.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Efecto de elevación al pasar el mouse */
    .card { transition: all 0.3s ease; border: 1px solid #eee !important; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .btn-primary { background-color: #4e73df; border: none; }
    .btn-primary:hover { background-color: #2e59d9; transform: scale(1.02); }
</style>
@endsection