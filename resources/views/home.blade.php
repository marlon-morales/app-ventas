@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <h2 class="text-primary">Panel de Control - {{ Auth::user()->name }}</h2>
                    <p>Gestiona las ventas de tu empresa hoy.</p>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
</div>
@endsection