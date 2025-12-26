@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: #f8f9fa; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        <div class="col-lg-4 bg-info p-5 text-white d-none d-lg-block">
                            <h2 class="font-weight-bold mb-4">Editar Perfil</h2>
                            <p class="lead">Modificando el acceso de: <br><strong>{{ $usuario->name }}</strong></p>
                            <i class="fas fa-user-edit fa-5x mt-4 opacity-50"></i>
                        </div>

                        <div class="col-lg-8 p-5 bg-white">
                            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                                <?php echo csrf_field(); ?>
                                <h4 class="font-weight-bold text-dark mb-4">Información del Perfil</h4>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">NOMBRE COMPLETO</label>
                                        <input type="text" name="name" value="{{ $usuario->name }}" class="form-control form-control-lg shadow-sm border-0 bg-light" required style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">CORREO ELECTRÓNICO</label>
                                        <input type="email" name="email" value="{{ $usuario->email }}" class="form-control form-control-lg shadow-sm border-0 bg-light" required style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">NUEVA CONTRASEÑA (Opcional)</label>
                                        <input type="password" name="password" class="form-control form-control-lg shadow-sm border-0 bg-light" placeholder="Dejar en blanco para no cambiar" style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="small font-weight-bold text-muted">ROL DE USUARIO</label>
                                        <select name="rol" class="form-control form-control-lg shadow-sm border-0 bg-light" style="border-radius: 12px;">
                                            <option value="empleado" {{ $usuario->rol == 'empleado' ? 'selected' : '' }}>Empleado Estándar</option>
                                            <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                                        </select>
                                    </div>
                                </div>

                                <h4 class="font-weight-bold text-dark mt-4 mb-4">Permisos de Módulos</h4>
                                <div class="row">
                                    @php
                                        $modulos = [
                                            ['p_productos', 'Productos', 'fa-boxes', 'text-warning'],
                                            ['p_pedidos', 'Pedidos', 'fa-shopping-cart', 'text-success'],
                                            ['p_cocina', 'Cocina', 'fa-utensils', 'text-danger'],
                                            ['p_pagos', 'Pagos', 'fa-cash-register', 'text-primary'],
                                            ['p_informes', 'Informes', 'fa-chart-pie', 'text-info'],
                                            ['p_usuarios', 'Usuarios', 'fa-users-cog', 'text-dark']
                                        ];
                                    @endphp

                                    @foreach($modulos as $mod)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-0 bg-light shadow-sm p-3 h-100" style="border-radius: 15px;">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="{{ $mod[0] }}" class="custom-control-input" id="{{ $mod[0] }}"
                                                {{ $usuario->{$mod[0]} ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold" for="{{ $mod[0] }}">
                                                    <i class="fas {{ $mod[2] }} {{ $mod[3] }} mr-2"></i> {{ $mod[1] }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-5 d-flex justify-content-between">
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-light px-4 rounded-pill">Cancelar</a>
                                    <button type="submit" class="btn btn-info px-5 rounded-pill shadow font-weight-bold text-white">ACTUALIZAR USUARIO</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-switch .custom-control-label::before { height: 1.5rem; width: 2.5rem; border-radius: 2rem; border: none; background-color: #dee2e6; }
    .custom-switch .custom-control-label::after { width: calc(1.5rem - 4px); height: calc(1.5rem - 4px); background-color: #fff; border-radius: 2rem; top: calc(0.25rem + 2px); left: calc(-2.5rem + 2px); }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #17a2b8; }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(1rem); }
</style>
@endsection