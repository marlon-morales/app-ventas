@extends('layouts.app')

@section('content')
<style>
    /* Diseño Novedoso */
    .filter-btn {
        border-radius: 30px;
        transition: all 0.3s ease;
        border: 2px solid #ddd;
        margin: 5px;
        padding: 8px 20px;
        background-color: white;
        color: #555;
    }

    /* El botón "alumbrando" cuando está activo */
    .filter-btn.active {
        background-color: #ff9f43 !important; /* Naranja llamativo */
        color: white !important;
        border-color: #ff6b6b;
        box-shadow: 0 4px 15px rgba(255, 159, 67, 0.4);
        transform: scale(1.05);
    }
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
                @foreach($productos as $producto)
                <div class="col-md-3 product-item" data-category="{{ strtolower($producto->categoria) }}">
                    <div class="card mb-3 add-to-cart"
                         data-id="{{ $producto->id_producto }}"
                         data-nombre="{{ $producto->nombre }}"
                         data-precio="{{ $producto->precio }}"
                         style="cursor: pointer; border-radius: 15px;">

                        <img src="{{ asset('uploads/productos/'.$producto->imagen) }}" class="card-img-top" style="height: 120px; object-fit: cover; border-radius: 15px 15px 0 0;">

                        <div class="card-body p-2 text-center">
                            <h6 class="font-weight-bold mb-0 text-truncate">{{ $producto->nombre }}</h6>
                            <small class="text-success font-weight-bold">${{ number_format($producto->precio, 0) }}</small>
                            <input type="hidden" class="product-cat-hidden" value="{{ strtolower($producto->categoria) }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-4 py-3 shadow-lg bg-white" style="height: 100vh; display: flex; flex-direction: column;">

            <div class="px-2 mb-3 d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold m-0">🛒 Pedido Actual</h5>
                <button class="btn btn-sm btn-outline-danger border-0" onclick="vaciarCarrito()">Eliminar Todo</button>
            </div>

            <div id="cart-scroll-area" class="flex-grow-1 px-2" style="overflow-y: auto; height: 80vh; scrollbar-width: thin;">

                <div class="form-group px-3">
                    <input type="text" id="cliente_nombre" class="form-control rounded-pill bg-light border-0 text-center" placeholder="👤 Nombre del Cliente">
                </div>

                <div id="cart-items">
                    </div>
            </div>

            <div class="px-2 pt-3 border-top bg-white text-center" style="height: 20vh;">
                <span class="text-muted small text-uppercase font-weight-bold">Total de la Cuenta</span>
                <h2 class="font-weight-bold text-primary mb-3" id="cart-total">$0</h2>

                <div class="row no-gutters px-2">
                    <div class="col-4 pr-1">
                        <button class="btn btn-light btn-block rounded-pill font-weight-bold" onclick="location.reload()">Cancelar</button>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-warning btn-block rounded-pill font-weight-bold shadow-sm" id="btn-enviar-cocina">
                            🔥 ENVIAR A COCINA
                        </button>
                    </div>
                </div>
            </div>


        </div>


    </div>
</div>

<script>
    let cart = {};

    document.addEventListener('DOMContentLoaded', function() {
        // --- FILTRADO ---
        const filterButtons = document.querySelectorAll('.filter-btn');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // 1. Cambiar estado visual de los botones
                filterButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
                this.classList.add('active', 'btn-dark');

                // 2. Obtener la categoría seleccionada (ej: 'asados')
                const selectedFilter = this.dataset.filter.toLowerCase().trim();

                // 3. Filtrar los productos
                document.querySelectorAll('.product-item').forEach(item => {
                    const itemCategory = item.dataset.category.toLowerCase().trim();

                    if (selectedFilter === 'all' || itemCategory === selectedFilter) {
                        item.style.setProperty('display', 'block', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        });

        // --- AGREGAR ---
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const itemContenedor = this.closest('.product-item');
                if (cart[id]) {
                    cart[id].qty++;
                } else {
                    cart[id] = {
                        nombre: this.dataset.nombre,
                        precio: parseFloat(this.dataset.precio),
                        categoria: itemContenedor.dataset.category,
                        imagen: itemContenedor.querySelector('img').src,
                        qty: 1
                    };
                }
                renderCart();
            });
        });
    });

    function renderCart() {
        const cartContainer = document.getElementById('cart-items');
        const totalContainer = document.getElementById('cart-total');
        cartContainer.innerHTML = '';
        let totalGeneral = 0;

        const itemsIds = Object.keys(cart);
        if (itemsIds.length === 0) {
            cartContainer.innerHTML = '<div class="text-center mt-5 text-muted">No hay productos</div>';
            totalContainer.innerText = '$0';
            return;
        }

        const colores = {'entrada': '#e3f2fd', 'plato fuerte': '#f1f8e9', 'bebidas': '#fff3e0', 'asados': '#fce4ec', 'postres': '#f3e5f5', 'default': '#f8f9fa'};
        const categoriasPresentes = [...new Set(Object.values(cart).map(i => i.categoria))];

        categoriasPresentes.forEach(cat => {
            const bg = colores[cat] || colores['default'];
            let html = `<div class="mb-4 p-3 shadow-sm" style="background-color: ${bg}; border-radius: 20px;">
                <div class="small font-weight-bold text-uppercase mb-3 text-center text-secondary">✨ ${cat} ✨</div>`;

            itemsIds.forEach(id => {
                const item = cart[id];
                if (item.categoria === cat) {
                    totalGeneral += (item.precio * item.qty);
                    html += `
                        <div class="card border-0 shadow-sm mb-2" style="border-radius: 15px;">
                            <div class="card-body p-2 d-flex align-items-center">
                                <button onclick="deleteProduct('${id}')" class="btn btn-sm btn-outline-danger border-0 mr-2" title="Eliminar">&times;</button>

                                <img src="${item.imagen}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 10px;" class="mr-2">

                                <div class="flex-grow-1 text-left">
                                    <div class="small font-weight-bold text-dark text-truncate" style="max-width:80px;">${item.nombre}</div>
                                    <div class="text-success small font-weight-bold">$${(item.precio * item.qty).toLocaleString()}</div>
                                </div>

                                <div class="d-flex align-items-center bg-light rounded-pill px-2">
                                    <button class="btn btn-sm btn-link text-dark p-0" onclick="changeQty('${id}', -1)">-</button>
                                    <span class="mx-2 small font-weight-bold">${item.qty}</span>
                                    <button class="btn btn-sm btn-link text-dark p-0" onclick="changeQty('${id}', 1)">+</button>
                                </div>
                            </div>
                        </div>`;
                }
            });
            html += `</div>`;
            cartContainer.innerHTML += html;
        });
        totalContainer.innerText = '$' + totalGeneral.toLocaleString();
    }

    function changeQty(id, delta) {
        if (cart[id]) {
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }
    }

    function deleteProduct(id) {
        delete cart[id];
        renderCart();
    }

    // --- CORRECCIÓN BOTÓN ENVIAR A COCINA ---
    document.addEventListener('click', function(e) {
        if(e.target && e.target.id == 'btn-enviar-cocina'){
            const cliente = document.getElementById('cliente_nombre').value;
            const itemsArray = Object.keys(cart).map(id => ({
                id: id,
                qty: cart[id].qty,
                precio: cart[id].precio
            }));

            if (!cliente) { alert("👤 Escribe el nombre del cliente"); return; }
            if (itemsArray.length === 0) { alert("🛒 Agrega productos al pedido"); return; }

            // Bloqueamos el botón para evitar múltiples clics
            e.target.disabled = true;
            e.target.innerText = "Enviando...";

            fetch("{{ route('ventas.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
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
                    alert("✅ ¡Pedido enviado a cocina!");
                    location.reload();
                } else {
                    alert("❌ Error: " + data.error);
                    e.target.disabled = false;
                    e.target.innerText = "🔥 ENVIAR A COCINA";
                }
            })
            .catch(err => {
                console.error(err);
                alert("❌ Error de conexión al servidor");
                e.target.disabled = false;
                e.target.innerText = "🔥 ENVIAR A COCINA";
            });
        }
    });
</script>
@endsection