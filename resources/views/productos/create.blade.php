@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white text-center font-weight-bold">
                    🚀 Registrar Nuevo Producto
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group text-center">
                            <div class="mb-3">
                                <img id="preview" src="https://via.placeholder.com/150"
                                     class="img-thumbnail"
                                     style="width: 160px; height: 160px; object-fit: cover; border-radius: 20px; border: 3px solid #f8f9fa;">
                            </div>
                            <label class="btn btn-outline-secondary btn-sm">
                                Seleccionar Imagen
                                <input type="file" name="imagen" id="imagen_input" hidden accept="image/*" required>
                            </label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre del Producto</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categoría / Tipo</label>
                                    <select name="categoria" class="form-control" required>
                                        <option value="entrada">Entradas</option>
                                        <option value="plato fuerte">Plato Fuerte</option>
                                        <option value="asados">Asados</option>
                                        <option value="bebidas">Bebidas</option>
                                        <option value="cocteles">Cocteles</option>
                                        <option value="helados">Helados</option>
                                        <option value="postres">Postres</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Precio de Venta ($)</label>
                                    <input type="number" name="precio" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="text" name="descripcion" class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow mt-3 py-2 font-weight-bold">
                            💾 Guardar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imagen_input').addEventListener('change', function(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('preview').setAttribute('src', event.target.result);
        }
        if(this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection