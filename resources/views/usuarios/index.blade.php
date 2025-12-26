@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0" style="border-radius: 15px;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4">
            <h3 class="font-weight-bold mb-0">👥 Gestión de Usuarios</h3>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th class="text-center">Permisos Activos</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                        <tr>
                            <td class="px-4 font-weight-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge badge-info">{{ $user->rol }}</span></td>
                            <td class="text-center">
                                @if($user->p_productos) <span class="badge badge-pill badge-light border">Prod</span> @endif
                                @if($user->p_pedidos) <span class="badge badge-pill badge-light border">Ped</span> @endif
                                @if($user->p_cocina) <span class="badge badge-pill badge-light border">Coc</span> @endif
                                @if($user->p_pagos) <span class="badge badge-pill badge-light border">Pag</span> @endif
                                @if($user->p_informes) <span class="badge badge-pill badge-light border">Inf</span> @endif
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-dark mx-1"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-outline-danger mx-1"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection