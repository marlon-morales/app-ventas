@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0" style="border-radius: 25px;">
                <div class="card-header bg-dark text-white text-center py-4">
                    <h3 class="mb-0 font-weight-bold">✏️ Editar Producto</h3>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">

                        <div class="text-center mb-5">
                            <div class="d-inline-block position-relative">
                                @php

                                    $nombreImagen = $producto->imagen;
                                    $urlFinal = ($nombreImagen) ? asset('uploads/productos/' . $nombreImagen) : 'https://via.placeholder.com/200?text=Sin+Imagen';
                                @endphp

                                <img id="preview"
                                     src="{{ $urlFinal }}"
                                     class="img-thumbnail shadow-sm"
                                     style="width: 220px; height: 220px; object-fit: cover; border-radius: 20px; border: 4px solid #f8f9fa;"
                                     onerror="this.src='https://via.placeholder.com/200?text=No+Encontrada';">

                                <label for="imagen_input" class="btn btn-warning btn-sm position-absolute" style="bottom: 10px; right: 10px; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="fas fa-camera text-dark"></i>
                                    <input type="file" name="imagen" id="imagen_input" hidden accept="image/*">
                                </label>
                            </div>
                            <p class="small text-muted mt-2">Ubicación: <span class="badge badge-light">uploads/productos/{{ $nombreImagen ?? 'ninguno' }}</span></p>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted">NOMBRE DEL PRODUCTO</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required style="border-radius: 12px;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="small font-weight-bold text-muted">PRECIO ($)</label>
                                <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" required style="border-radius: 12px;">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small font-weight-bold text-muted">CATEGORÍA</label>
                                <select name="categoria" class="form-control" style="border-radius: 12px;">
                                    <option value="Entrada" {{ $producto->categoria == 'Entrada' ? 'selected' : '' }}>Entradas</option>
                                    <option value="Plato Fuerte" {{ $producto->categoria == 'Plato Fuerte' ? 'selected' : '' }}>Plato Fuerte</option>
                                    <option value="Bebidas" {{ $producto->categoria == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                                    <option value="Asados" {{ $producto->categoria == 'Asados' ? 'selected' : '' }}>Asados</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted">DESCRIPCIÓN DEL PRODUCTO</label>
                            <textarea name="descripcion" class="form-control" rows="3" style="border-radius: 12px;">{{ $producto->descripcion }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold shadow-lg py-3" style="border-radius: 15px;">
                            <i class="fas fa-save mr-2"></i> ACTUALIZAR INFORMACIÓN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imagen_input').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection