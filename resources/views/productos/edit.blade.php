@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card shadow-lg border-0" style="border-radius: 25px; overflow: hidden;">

            <div class="card-header bg-dark text-white text-center py-4">
                <h3 class="mb-0 font-weight-bold">✏️ Editar Producto</h3>
                <p class="small mb-0 text-warning">ID: #{{ $producto->id_producto }}</p>
            </div>

            <div class="card-body p-5">
                <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-group mb-4 text-center">
                        <label class="font-weight-bold text-uppercase small text-muted">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control form-control-lg text-center shadow-sm"
                               value="{{ $producto->nombre }}" required style="border-radius: 15px;">
                    </div>

                    <div class="form-group mb-4 text-center">
                        <label class="font-weight-bold text-uppercase small text-muted">Precio de Venta</label>
                        <input type="number" name="precio" class="form-control form-control-lg text-center shadow-sm"
                               value="{{ $producto->precio }}" required style="border-radius: 15px;">
                    </div>

                    <div class="form-group mb-4 text-center">
                        <label class="font-weight-bold text-uppercase small text-muted">Categoría / Tipo</label>
                        <select name="categoria" class="form-control form-control-lg shadow-sm" style="border-radius: 15px; text-align-last: center;">
                            <option value="Entrada" {{ $producto->categoria == 'Entrada' ? 'selected' : '' }}>Entradas</option>
                            <option value="Plato Fuerte" {{ $producto->categoria == 'Plato Fuerte' ? 'selected' : '' }}>Plato Fuerte</option>
                            <option value="Bebidas" {{ $producto->categoria == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                            <option value="Asados" {{ $producto->categoria == 'Asados' ? 'selected' : '' }}>Asados</option>
                            <option value="Postres" {{ $producto->categoria == 'Postres' ? 'selected' : '' }}>Postres</option>
                        </select>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-warning btn-block btn-lg font-weight-bold shadow py-3" style="border-radius: 15px;">
                            💾 GUARDAR CAMBIOS
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-link btn-block text-muted mt-2">
                            Cancelar y volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection