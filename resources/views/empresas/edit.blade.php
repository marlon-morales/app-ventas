@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-lg" style="border-radius: 25px;">
        <div class="card-body p-5">
            <h2 class="font-weight-bold text-dark mb-4">
                <i class="fas fa-edit text-info mr-2"></i> Gestión de Empresa: {{ $empresa->nombre_empresa }}
            </h2>

            <form action="{{ route('empresas.update', $empresa->id_empresa) }}" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-7 border-right">
                        <h5 class="text-uppercase text-muted font-weight-bold mb-4">Perfil del Negocio</h5>

                        <div class="form-group text-center mb-4">
                            @if($empresa->logo)
                                <img src="{{ asset('logos/'.$empresa->logo) }}" class="shadow-sm mb-2" style="width: 120px; border-radius: 15px;">
                            @endif
                            <input type="file" name="logo" class="form-control-file mt-2">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nombre Comercial</label>
                            <input type="text" name="nombre_empresa" value="{{ $empresa->nombre_empresa }}" class="form-control bg-light border-0 p-4" style="border-radius: 12px;">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control bg-light border-0" rows="3" style="border-radius: 12px;">{{ $empresa->descripcion }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Teléfono</label>
                                <input type="text" name="telefono" value="{{ $empresa->telefono }}" class="form-control bg-light border-0" style="border-radius: 12px;">
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Ciudad</label>
                                <input type="text" name="ciudad" value="{{ $empresa->ciudad }}" class="form-control bg-light border-0" style="border-radius: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h5 class="text-uppercase text-muted font-weight-bold mb-4">Cuenta Administrativa</h5>

                        @if(!$admin)
                            <div class="alert alert-warning rounded-pill small">
                                <i class="fas fa-exclamation-triangle mr-2"></i> No se encontró un administrador vinculado.
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold">Nombre del Dueño</label>
                            <input type="text" name="name" value="{{ $admin->name ?? '' }}" class="form-control border-info" required style="border-radius: 12px; background: #f0faff;">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Correo de Acceso</label>
                            <input type="email" name="email" value="{{ $admin->email ?? '' }}" class="form-control border-info" required style="border-radius: 12px; background: #f0faff;">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-danger">Cambiar Contraseña</label>
                            <input type="password" name="password" placeholder="Dejar vacío para no cambiar" class="form-control" style="border-radius: 12px;">
                        </div>
                    </div>
                </div>

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow" style="border-radius: 50px;">
                        <i class="fas fa-check-circle mr-2"></i> GUARDAR CAMBIOS GLOBALES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection