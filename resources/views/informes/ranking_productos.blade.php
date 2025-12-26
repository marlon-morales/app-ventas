@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="text-center mb-5">
        <h2 class="font-weight-bold text-dark">🏆 Ranking de Productos</h2>
        <div class="d-flex justify-content-center align-items-center mt-3 flex-wrap">
            <form action="{{ route('informes.ranking_productos') }}" method="GET" class="form-inline m-2">
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control shadow-sm border-0 mr-2" style="border-radius: 8px;">
                <button type="submit" class="btn btn-primary shadow-sm px-4" style="border-radius: 8px;">🔍 Consultar Fecha</button>
            </form>
            <a href="{{ route('informes.ranking_productos') }}" class="btn btn-info shadow-sm px-4 m-2 text-white" style="border-radius: 8px; background-color: #17a2b8;">
                📊 Ver Histórico General
            </a>
        </div>
        @if(!$fecha)
            <span class="badge badge-secondary p-2 mt-2">Mostrando: Histórico General (Todas las ventas)</span>
        @else
            <span class="badge badge-info p-2 mt-2">Mostrando: Ventas del día {{ $fecha }}</span>
        @endif
    </div>

    <ul class="nav nav-pills justify-content-center mb-5" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active font-weight-bold px-4 py-2 shadow-sm mr-3"
               id="pills-mas-tab" data-toggle="pill" href="#tab-mas" role="tab"
               style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 12px;">
               📈 MÁS VENDIDOS
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold px-4 py-2 shadow-sm"
               id="pills-menos-tab" data-toggle="pill" href="#tab-menos" role="tab"
               style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 12px;">
               📉 MENOS VENDIDOS
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-mas">
            <div class="row justify-content-center">
                @forelse($masVendidos as $index => $prod)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm text-center p-3" style="border-radius: 15px; border-top: 5px solid {{ $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : ($index == 2 ? '#CD7F32' : '#28a745')) }};">
                        <span class="small font-weight-bold text-muted">#{{ $index + 1 }}</span>

                        <div style="width: 100px; height: 100px; margin: 15px auto; overflow: hidden; border-radius: 10px; background: #eee; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                            <img src="{{ $prod->imagen ? asset('uploads/productos/' . $prod->imagen) : asset('img/no-image.png') }}"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($prod->nombre) }}&background=random';">
                        </div>

                        <h6 class="font-weight-bold mb-1" style="height: 2.5rem; overflow: hidden;">{{ $prod->nombre }}</h6>
                        <p class="text-success font-weight-bold mb-2">${{ number_format($prod->precio, 0) }}</p>
                        <span class="badge badge-success p-2" style="background-color: #d4edda; color: #155724;">{{ $prod->total_vendido }} Vendidos</span>
                    </div>
                </div>
                @empty
                <p class="text-muted">No hay datos de ventas.</p>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="tab-menos">
            <div class="row justify-content-center">
                @forelse($menosVendidos as $index => $prod)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm text-center p-3" style="border-radius: 15px; border-top: 5px solid #dc3545;">
                        <span class="small font-weight-bold text-muted">#{{ $index + 1 }}</span>

                        <div style="width: 100px; height: 100px; margin: 15px auto; overflow: hidden; border-radius: 10px; background: #eee; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                            <img src="{{ $prod->imagen ? asset('uploads/productos/' . $prod->imagen) : asset('img/no-image.png') }}"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($prod->nombre) }}&background=f8d7da&color=721c24';">
                        </div>

                        <h6 class="font-weight-bold mb-1" style="height: 2.5rem; overflow: hidden;">{{ $prod->nombre }}</h6>
                        <p class="text-dark font-weight-bold mb-2">${{ number_format($prod->precio, 0) }}</p>
                        <span class="badge badge-danger p-2" style="background-color: #f8d7da; color: #721c24;">{{ $prod->total_vendido }} Ventas</span>
                    </div>
                </div>
                @empty
                <p class="text-muted">No hay datos de ventas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Efecto al seleccionar pestañas para que no pierdan el color */
    .nav-pills .nav-link.active#pills-mas-tab { background-color: #c3e6cb !important; color: #155724 !important; }
    .nav-pills .nav-link.active#pills-menos-tab { background-color: #f5c6cb !important; color: #721c24 !important; }
</style>
@endsection