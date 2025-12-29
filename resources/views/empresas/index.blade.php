@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-layer-group text-primary mr-2"></i> Directorio de Empresas
                </h3>
                <a href="{{ route('empresas.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> NUEVA EMPRESA
                </a>
            </div>

            <div class="row mb-4 bg-light p-3 mx-1" style="border-radius: 15px;">
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted">CÓDIGO</label>
                    <input type="text" id="buscarCodigo" class="form-control border-0 shadow-sm" placeholder="ID..." style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">NOMBRE EMPRESA</label>
                    <input type="text" id="buscarNombre" class="form-control border-0 shadow-sm" placeholder="Buscar nombre..." style="border-radius: 8px;">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">CIUDAD</label>
                    <input type="text" id="buscarCiudad" class="form-control border-0 shadow-sm" placeholder="Filtrar ciudad..." style="border-radius: 8px;">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">ADMINISTRADOR / CORREO</label>
                    <input type="text" id="buscarAdmin" class="form-control border-0 shadow-sm" placeholder="Nombre o email..." style="border-radius: 8px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaEmpresas">
                    <thead class="thead-dark">
                        <tr class="small text-uppercase">
                            <th class="border-0" style="border-radius: 10px 0 0 0;">ID</th>
                            <th class="border-0">Logo</th>
                            <th class="border-0">Nombre</th>
                            <th class="border-0">Descripción</th>
                            <th class="border-0">Ubicación</th>
                            <th class="border-0">Contacto</th>
                            <th class="border-0">Administrador</th>
                            <th class="border-0 text-center" style="border-radius: 0 10px 0 0;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empresas as $empresa)
                        <tr class="empresa-row">
                            <td class="font-weight-bold col-codigo">{{ $empresa->id_empresa }}</td>
                            <td class="text-center">
                                @if($empresa->logo)
                                    <img src="{{ asset('logos/' . $empresa->logo) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building small"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="col-nombre">
                                <span class="font-weight-bold text-primary">{{ $empresa->nombre_empresa }}</span>
                            </td>
                            <td class="small text-muted">
                                {{-- SOLUCIÓN AL ERROR: Usamos mb_strimwidth de PHP nativo para evitar el error de Clase Str --}}
                                {{ mb_strimwidth($empresa->descripcion, 0, 45, "...") }}
                            </td>
                            <td class="small">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                <strong class="col-ciudad">{{ $empresa->ciudad }}</strong><br>
                                <span class="text-muted">{{ $empresa->direccion }}</span>
                            </td>
                            <td class="small">
                                <a href="tel:{{ $empresa->telefono }}" class="text-decoration-none text-dark">
                                    <i class="fas fa-phone-alt text-success mr-1"></i> {{ $empresa->telefono }}
                                </a>
                            </td>
                            <td class="col-admin">
                                @if($empresa->admin)
                                    <div class="small font-weight-bold">{{ $empresa->admin->name }}</div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">{{ $empresa->admin->email }}</div>
                                @else
                                    <span class="badge badge-warning font-weight-light">Sin asignar</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('empresas.edit', $empresa->id_empresa) }}" class="btn btn-info btn-sm rounded-circle shadow-sm">
                                    <i class="fas fa-pencil-alt text-white"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT DE FILTRADO (Mantener al final del archivo) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function filtrarTabla() {
        let codigo = $("#buscarCodigo").val().toLowerCase();
        let nombre = $("#buscarNombre").val().toLowerCase();
        let ciudad = $("#buscarCiudad").val().toLowerCase();
        let admin  = $("#buscarAdmin").val().toLowerCase();

        $("#tablaEmpresas tbody tr").each(function() {
            let tCodigo = $(this).find(".col-codigo").text().toLowerCase();
            let tNombre = $(this).find(".col-nombre").text().toLowerCase();
            let tCiudad = $(this).find(".col-ciudad").text().toLowerCase();
            let tAdmin  = $(this).find(".col-admin").text().toLowerCase();

            if (tCodigo.includes(codigo) && tNombre.includes(nombre) && tCiudad.includes(ciudad) && tAdmin.includes(admin)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $("#buscarCodigo, #buscarNombre, #buscarCiudad, #buscarAdmin").on("keyup", filtrarTabla);
});
</script>
@endsection