@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0" style="border-radius: 20px;">
        <div class="card-header bg-dark text-white p-4">
            <h3 class="mb-0">🔍 Buscador y Gestión de Productos</h3>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4 bg-light p-3 rounded shadow-sm mx-1 border">

                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase">🔢 ID / Código</label>
                    <input type="text" id="filterID" class="form-control border-0 shadow-sm" placeholder="Ej: 10">
                </div>

                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase">📝 Nombre del Producto</label>
                    <input type="text" id="filterName" class="form-control border-0 shadow-sm" placeholder="Buscar nombre...">
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase">🍴 Categoría</label>
                    <select id="filterCategory" class="form-control border-0 shadow-sm">
                        <option value="">Todas las categorías</option>
                        <option value="Entrada">Entradas</option>
                        <option value="Plato Fuerte">Plato Fuerte</option>
                        <option value="Bebidas">Bebidas</option>
                        <option value="Asados">Asados</option>
                        <option value="Postres">Postres</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="productTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Código (ID)</th>
                            <th>Miniatura</th>
                            <th>Nombre / Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $prod)
                        <tr class="product-row"> <td class="col-id">#{{ $prod->id_producto }}</td>
                            <td><img src="{{ asset('uploads/productos/'.$prod->imagen) }}" width="50"></td>
                            <td class="col-nombre">{{ $prod->nombre }}</td>
                            <td class="col-categoria">{{ $prod->categoria }}</td>
                            <td class="col-precio">${{ number_format($prod->precio, 0) }}</td>
                            <td>
                                <a href="{{ route('productos.edit', $prod->id_producto) }}" class="btn btn-warning btn-sm">Editar</a>
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
    // 1. Capturamos los 3 elementos del filtro
    const inputID = document.getElementById('filterID');
    const inputName = document.getElementById('filterName');
    const selectCat = document.getElementById('filterCategory');

    // 2. Función que hace la magia de filtrar
    function filtrarTabla() {
        const valID = inputID.value.toLowerCase().trim();
        const valName = inputName.value.toLowerCase().trim();
        const valCat = selectCat.value.toLowerCase().trim();

        // Buscamos todas las filas que tengan la clase 'product-row'
        const filas = document.querySelectorAll('.product-row');

        filas.forEach(fila => {
            // Obtenemos el texto de cada columna específica
            const textoID = fila.querySelector('.col-id').innerText.toLowerCase();
            const textoNombre = fila.querySelector('.col-nombre').innerText.toLowerCase();
            const textoCategoria = fila.querySelector('.col-categoria').innerText.toLowerCase();

            // Lógica: ¿La fila cumple con los 3 filtros a la vez?
            const coincideID = textoID.includes(valID);
            const coincideNombre = textoNombre.includes(valName);
            const coincideCat = (valCat === "") || textoCategoria.includes(valCat);

            // Si coincide todo, mostramos la fila, si no, la ocultamos
            if (coincideID && coincideNombre && coincideCat) {
                fila.style.display = "";
            } else {
                fila.style.display = "none";
            }
        });
    }

    // 3. Escuchamos cuando el usuario escribe o cambia algo
    inputID.addEventListener('keyup', filtrarTabla);
    inputName.addEventListener('keyup', filtrarTabla);
    selectCat.addEventListener('change', filtrarTabla);
});
</script>
@endsection