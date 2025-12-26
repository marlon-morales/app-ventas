@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 font-weight-bold">➕ Añadir más productos al Pedido #{{ $pedido->id_pedido }}</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="lista-productos">
                        @foreach($productos as $p)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 btn-add-extra" style="cursor: pointer; border-radius: 15px;"
                                 onclick="agregarAlPedido('{{ $p->id_producto }}', '{{ $p->nombre }}', {{ $p->precio }})">
                                <img src="{{ asset('uploads/productos/'.$p->imagen) }}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <h6 class="small font-weight-bold">{{ $p->nombre }}</h6>
                                    <span class="text-success">${{ number_format($p->precio, 0) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <form action="{{ route('pedidos.update_pedido', $pedido->id_pedido) }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">

                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-header bg-warning text-dark text-center">
                        <h4 class="mb-0 font-weight-bold">Resumen a Modificar</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Cliente:</label>
                            <input type="text" name="cliente_nombre" class="form-control font-weight-bold" value="{{ $pedido->cliente_nombre }}">
                        </div>
                        <hr>
                        <div id="carrito-edicion">
                            @foreach($pedido->detalles as $det)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded shadow-sm item-pedido" data-id="{{ $det->id_producto }}">
                                <div>
                                    <input type="number" name="productos[{{ $det->id_producto }}][cantidad]"
                                           class="form-control-sm border-primary text-center mr-2"
                                           value="{{ $det->cantidad }}" style="width: 60px; font-size: 1.1rem; font-weight: bold;">
                                    <span class="font-weight-bold">{{ $det->producto->nombre }}</span>
                                </div>
                                <span class="font-weight-bold text-success">${{ number_format($det->precio_unitario * $det->cantidad, 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow rounded-pill">
                            ✅ GUARDAR CAMBIOS EN EL PEDIDO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection