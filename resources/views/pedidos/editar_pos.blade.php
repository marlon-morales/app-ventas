@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <h5 class="mb-0 font-weight-bold">🛍️ Editar Pedido #{{ $pedido->id_pedido }}</h5>
                        </div>
                        <div class="col-md-7">
                            <input type="text" id="busquedaRealTime" class="form-control rounded-pill border-0 shadow-sm" placeholder="🔍 Escribe para buscar producto...">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap mt-3">
                        <button class="btn btn-sm btn-warning mx-1 mb-1 filter-btn active" data-filter="all">TODOS</button>
                        <button class="btn btn-sm btn-outline-warning mx-1 mb-1 filter-btn" data-filter="entrada">ENTRADAS</button>
                        <button class="btn btn-sm btn-outline-warning mx-1 mb-1 filter-btn" data-filter="plato fuerte">PLATOS FUERTES</button>
                        <button class="btn btn-sm btn-outline-warning mx-1 mb-1 filter-btn" data-filter="asados">ASADOS</button>
                        <button class="btn btn-sm btn-outline-warning mx-1 mb-1 filter-btn" data-filter="bebidas">BEBIDAS</button>
                        <button class="btn btn-sm btn-outline-warning mx-1 mb-1 filter-btn" data-filter="postres">POSTRES</button>
                    </div>
                </div>

                <div class="card-body" style="height: 70vh; overflow-y: auto; background-color: #f4f7f6;">
                    <div class="row" id="contenedor-productos">
                        @foreach($productos as $p)
                        <div class="col-md-4 mb-3 product-item" data-category="{{ trim(strtolower($p->categoria)) }}" data-nombre="{{ trim(strtolower($p->nombre)) }}">
                            <div class="card h-100 shadow-sm border-0" onclick="agregarAlCarrito('{{ $p->id_producto }}', '{{ $p->nombre }}', {{ $p->precio }}, '{{ asset('uploads/productos/'.$p->imagen) }}', '{{ $p->categoria }}')" style="cursor: pointer; border-radius: 15px;">
                                <img src="{{ asset('uploads/productos/'.$p->imagen) }}" class="card-img-top" style="height: 110px; object-fit: cover; border-radius: 15px 15px 0 0;">
                                <div class="card-body p-2 text-center">
                                    <h6 class="small font-weight-bold mb-1">{{ $p->nombre }}</h6>
                                    <span class="badge badge-pill badge-light border text-muted mb-1">{{ $p->categoria }}</span><br>
                                    <span class="text-success font-weight-bold">${{ number_format($p->precio, 0) }}</span>
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

                <div class="card shadow-lg border-0" style="border-radius: 20px; height: 85vh;">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted small">NOMBRE DEL CLIENTE:</label>
                            <input type="text" name="cliente_nombre" class="form-control form-control-lg border-warning font-weight-bold" value="{{ $pedido->cliente_nombre }}" required>
                        </div>

                        <div id="lista-carrito" class="flex-grow-1" style="overflow-y: auto;">
                            </div>

                        <div class="border-top pt-3 mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="font-weight-bold mb-0">TOTAL:</h3>
                                <h3 class="font-weight-bold text-success mb-0" id="total-pedido">$0</h3>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block btn-lg font-weight-bold shadow rounded-pill mt-3">
                                💾 ACTUALIZAR PEDIDO
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Usamos un Objeto para manejar el carrito por ID de producto
    let carrito = {};

    // CARGA INICIAL: Sembramos el carrito con lo que ya existe
    @foreach($pedido->detalles as $det)
        carrito["{{ $det->producto_id }}"] = {
            id: "{{ $det->producto_id }}",
            nombre: "{{ $det->producto->nombre }}",
            precio: {{ $det->precio_unitario }},
            imagen: "{{ asset('uploads/productos/' . $det->producto->imagen) }}",
            categoria: "{{ $det->producto->categoria }}",
            cantidad: {{ $det->cantidad }}
        };
    @endforeach

    function agregarAlCarrito(id, nombre, precio, imagen, categoria) {
        if (carrito[id]) {
            carrito[id].cantidad++;
        } else {
            // Creamos una entrada nueva en el objeto sin afectar las anteriores
            carrito[id] = { id, nombre, precio, imagen, categoria, cantidad: 1 };
        }
        actualizarVistaCarrito();
    }

    function cambiarCantidad(id, cambio) {
        if (carrito[id]) {
            carrito[id].cantidad += cambio;
            if (carrito[id].cantidad <= 0) delete carrito[id];
            actualizarVistaCarrito();
        }
    }

    function actualizarVistaCarrito() {
        const contenedor = document.getElementById('lista-carrito');
        let total = 0;
        contenedor.innerHTML = '';

        // Recorremos el objeto carrito
        Object.values(carrito).forEach(item => {
            total += (item.precio * item.cantidad);

            // IMPORTANTE: El name="productos[ID_PRODUCTO][cantidad]" asegura unicidad
            contenedor.innerHTML += `
                <div class="d-flex align-items-center mb-2 p-2 bg-white rounded border shadow-sm">
                    <img src="${item.imagen}" style="width: 40px; height: 40px; object-fit: cover;" class="mr-2 rounded">
                    <div class="flex-grow-1">
                        <div class="font-weight-bold small">${item.nombre}</div>
                        <input type="hidden" name="productos[${item.id}][cantidad]" value="${item.cantidad}">
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-light border" onclick="cambiarCantidad('${item.id}', -1)">-</button>
                        <span class="mx-2 font-weight-bold">${item.cantidad}</span>
                        <button type="button" class="btn btn-sm btn-light border" onclick="cambiarCantidad('${item.id}', 1)">+</button>
                    </div>
                </div>`;
        });
        document.getElementById('total-pedido').innerText = '$' + total.toLocaleString();
    }

    // Filtros de categoría (Fuerza bruta para asegurar que funcione)
    document.addEventListener('DOMContentLoaded', () => {
        actualizarVistaCarrito();

        const botones = document.querySelectorAll('.filter-btn');
        botones.forEach(btn => {
            btn.addEventListener('click', function() {
                botones.forEach(b => b.classList.replace('btn-warning', 'btn-outline-warning'));
                this.classList.replace('btn-outline-warning', 'btn-warning');

                const filtro = this.getAttribute('data-filter').toLowerCase();
                document.querySelectorAll('.product-item').forEach(p => {
                    const cat = p.getAttribute('data-category').toLowerCase();
                    p.style.display = (filtro === 'all' || cat === filtro) ? 'block' : 'none';
                });
            });
        });
    });
</script>
@endsection