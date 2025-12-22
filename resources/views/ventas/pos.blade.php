@extends('layouts.app')

@section('content')
<style>
    /* Diseño Novedoso */
    .filter-btn { border-radius: 30px; transition: 0.3s; border: 2px solid #eee; margin: 0 5px; font-weight: 600; }
    .filter-btn.active { background-color: #e67e22; color: white; border-color: #e67e22; box-shadow: 0 4px 10px rgba(230,126,34,0.3); }
    .product-card { border-radius: 20px; transition: 0.3s; cursor: pointer; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .cart-container { border-radius: 25px 0 0 25px; background: #fff; }
    .badge-count { position: absolute; top: -5px; right: -5px; border-radius: 50%; }
</style>

<div class="container-fluid bg-light" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-8 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold text-dark">🍽️ Menú de Ventas</h4>
                <span class="badge badge-dark p-2" style="font-size: 1.1rem;">Pedido N°: <span id="num-pedido">{{ $proximoPedido }}</span></span>
            </div>

            <div class="d-flex mb-4 pb-2" style="overflow-x: auto; white-space: nowrap;">
                <button class="btn btn-light filter-btn active" data-filter="all">🌟 Todos</button>
                @foreach($categorias as $cat)
                    <button class="btn btn-light filter-btn text-capitalize" data-filter="{{ $cat }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <div class="row" id="product-grid">
                @foreach($productos as $prod)
                <div class="col-md-4 mb-4 product-item" data-category="{{ $prod->categoria }}">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ asset('uploads/productos/'.$prod->imagen) }}" class="card-img-top p-3" style="height: 180px; object-fit: cover; border-radius: 30px;">
                        </div>
                        <div class="card-body pt-0 text-center">
                            <h6 class="font-weight-bold mb-1">{{ $prod->nombre }}</h6>
                            <p class="text-muted small mb-2 text-truncate">{{ $prod->descripcion }}</p>
                            <h5 class="text-primary font-weight-bold">${{ number_format($prod->precio, 0) }}</h5>
                            <button class="btn btn-dark btn-sm rounded-pill px-4 add-to-cart"
                                    data-id="{{ $prod->id_producto }}"
                                    data-nombre="{{ $prod->nombre }}"
                                    data-precio="{{ $prod->precio }}">
                                Agregar +
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-4 py-4 cart-container shadow-lg">
            <div class="px-2 h-100 d-flex flex-column">
                <h5 class="font-weight-bold mb-4">🛒 Carrito de Compras</h5>

                <div class="form-group">
                    <label class="small font-weight-bold">Nombre del Cliente</label>
                    <input type="text" id="cliente_nombre" class="form-control rounded-pill border-0 bg-light" placeholder="¿A nombre de quién?">
                </div>

                <div id="cart-items" class="flex-grow-1 overflow-auto pr-2" style="max-height: 50vh;">
                    </div>

                <div class="mt-auto border-top pt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="font-weight-bold" id="cart-total">$0</span>
                    </div>
                    <button class="btn btn-warning btn-block btn-lg rounded-pill font-weight-bold shadow-sm py-3" id="btn-enviar-cocina">
                        🔥 ENVIAR A COCINA
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = {}; // Usaremos un objeto para manejar cantidades fácilmente



    // FILTRO DE CATEGORÍAS
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.onclick = function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            let filter = this.dataset.filter;
            document.querySelectorAll('.product-item').forEach(item => {
                item.style.display = (filter === 'all' || item.dataset.category === filter) ? 'block' : 'none';
            });
        }
    });

    // AGREGAR AL CARRITO
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.onclick = function() {
            const id = this.dataset.id;
            if(cart[id]) {
                cart[id].qty++;
            } else {
                cart[id] = {
                    nombre: this.dataset.nombre,
                    precio: parseFloat(this.dataset.precio),
                    qty: 1
                };
            }
            renderCart();
        }
    });

    // RENDERIZAR CARRITO CON EDICIÓN
    function renderCart() {
        const container = document.getElementById('cart-items');
        container.innerHTML = '';
        let total = 0;

        Object.keys(cart).forEach(id => {
            const item = cart[id];
            const subtotal = item.precio * item.qty;
            total += subtotal;

            container.innerHTML += `
                <div class="card mb-3 border-0 bg-light shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div style="width: 50%;">
                                <h6 class="mb-0 font-weight-bold text-truncate">${item.nombre}</h6>
                                <small class="text-success">$${item.precio.toLocaleString()}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-white shadow-sm" onclick="changeQty('${id}', -1)">-</button>
                                <span class="mx-3 font-weight-bold">${item.qty}</span>
                                <button class="btn btn-sm btn-white shadow-sm" onclick="changeQty('${id}', 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        if(Object.keys(cart).length === 0) {
            container.innerHTML = '<div class="text-center mt-5"><img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="80" class="opacity-50"><p class="text-muted mt-2">No hay productos aún</p></div>';
        }

        document.getElementById('cart-total').innerText = '$' + total.toLocaleString();
    }

    function changeQty(id, delta) {
        if(cart[id]) {
            cart[id].qty += delta;
            if(cart[id].qty <= 0) delete cart[id];
            renderCart();
        }
    }

    //logica para enviar a BD
    document.getElementById('btn-enviar-cocina').onclick = function() {
            const cliente = document.getElementById('cliente_nombre').value;

            // Convertimos nuestro objeto 'cart' en una lista simple para enviarla
            const itemsArray = Object.keys(cart).map(id => ({
                id: id,
                qty: cart[id].qty,
                precio: cart[id].precio
            }));

            // Validaciones básicas
            if (!cliente) {
                alert("⚠️ Por favor, escribe el nombre del cliente para identificar el pedido.");
                return;
            }
            if (itemsArray.length === 0) {
                alert("🛒 El carrito está vacío. Agrega algunos productos primero.");
                return;
            }

            // Esta es la "petición secreta" que viaja al servidor sin recargar la página
            fetch("{{ route('ventas.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Llave de seguridad de Laravel
                },
                body: JSON.stringify({
                    cliente: cliente,
                    items: itemsArray,
                    total: Object.values(cart).reduce((t, i) => t + (i.precio * i.qty), 0)
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert("✅ ¡Pedido #" + data.pedido_id + " enviado a cocina correctamente!");
                    location.reload(); // Recargamos la página para limpiar el carrito y el nombre
                } else {
                    alert("❌ Error al guardar: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Hubo un fallo en la conexión con el servidor.");
            });
        };
</script>
@endsection