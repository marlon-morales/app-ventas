@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="font-weight-bold mb-4 text-dark"><i class="fas fa-plus-circle text-primary"></i> Nueva Empresa SaaS</h2>

    <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                    <h5 class="text-primary font-weight-bold mb-4">Configuración de Negocio</h5>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold">LOGO DE LA EMPRESA</label>
                        <input type="file" name="logo" class="form-control-file border p-2 w-100" style="border-radius: 10px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase">Nombre Comercial</label>
                        <input type="text" name="nombre_empresa" class="form-control form-control-lg border-0 bg-light" placeholder="Ej. Burger King" required style="border-radius: 12px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase">Descripción</label>
                        <textarea name="descripcion" rows="2" class="form-control border-0 bg-light" placeholder="Breve descripción del negocio..." style="border-radius: 12px;"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted">CIUDAD</label>
                            <input type="text" name="ciudad" class="form-control border-0 bg-light" style="border-radius: 12px;">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="small font-weight-bold text-muted">DIRECCIÓN</label>
                            <input type="text" name="direccion" class="form-control border-0 bg-light" style="border-radius: 12px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">TELÉFONO DE CONTACTO</label>
                        <input type="text" name="telefono" class="form-control border-0 bg-light" style="border-radius: 12px;">
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 bg-dark text-white" style="border-radius: 20px;">
                    <h5 class="text-info font-weight-bold mb-4"><i class="fas fa-user-shield"></i> Credenciales de Dueño</h5>

                    <div class="form-group mb-3">
                        <label class="small">NOMBRE COMPLETO</label>
                        <input type="text" name="name" class="form-control border-0 bg-secondary text-white" required style="border-radius: 12px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small">EMAIL DE ACCESO</label>
                        <input type="email" name="email" class="form-control border-0 bg-secondary text-white" required style="border-radius: 12px;">
                    </div>

                    <div class="form-group mb-4">
                        <label class="small">CONTRASEÑA TEMPORAL</label>
                        <input type="password" name="password" class="form-control border-0 bg-secondary text-white" required style="border-radius: 12px;">
                    </div>

                    <p class="text-muted small italic">Nota: El usuario se creará como administrador con todos los permisos habilitados por defecto.</p>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4 shadow-lg font-weight-bold" style="border-radius: 15px; height: 60px;">
                    CREAR Y ACTIVAR EMPRESA
                </button>
            </div>
        </div>
    </form>
</div>
@endsection