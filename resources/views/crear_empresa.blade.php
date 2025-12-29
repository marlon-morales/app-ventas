@extends('layouts.app_simple') {{-- Un layout básico que debes crear --}}
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4>Registro de Nueva Arepería</h4></div>
                <div class="card-body">
                    <form action="{{ route('empresa.store') }}" method="POST">
                        {{ csrf_field() }}
                        
                        <h5>Datos de la Empresa</h5>
                        <div class="form-group">
                            <label>Nombre de la Empresa</label>
                            <input type="text" name="nombre_empresa" class="form-control" placeholder="Ej: ">
                        </div>

                        <hr>
                        <h5>Datos del Administrador</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nombre Completo</label>
                                <input type="text" name="admin_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input type="email" name="admin_email" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Contraseña</label>
                                <input type="password" name="admin_password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Confirmar Contraseña</label>
                                <input type="password" name="admin_password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-4">Finalizar Registro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection