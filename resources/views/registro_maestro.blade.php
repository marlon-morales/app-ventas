@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración Inicial - POS Arepas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; }
        .btn-primary { background-color: #e67e22; border: none; } /* Color naranja arepa */
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Registro de Nueva Sucursal y Administrador</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresa.store') }}" method="POST">
                        {{ csrf_field() }}

                        <h5 class="text-primary border-bottom pb-2">1. Datos de la Empresa</h5>
                        <div class="form-group">
                            <label>Nombre de la Empresa</label>
                            <input type="text" name="nombre_empresa" class="form-control" placeholder="Ej: Arepas El Primo Buga" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción / Lema</label>
                            <input type="text" name="descripcion" class="form-control" placeholder="Las mejores de la ciudad">
                        </div>

                        <h5 class="text-primary border-bottom pb-2 mt-4">2. Credenciales del Dueño/Admin</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nombre del Administrador</label>
                                <input type="text" name="admin_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Correo (Este será su Usuario)</label>
                                <input type="email" name="admin_email" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Contraseña</label>
                                <input type="password" name="admin_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Confirmar Contraseña</label>
                                <input type="password" name="admin_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">Activar Sistema</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>