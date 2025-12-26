@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 font-weight-bold text-dark">📈 Liquidación de Caja</h1>
            <p class="text-muted">Resumen detallado de operaciones por día</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('informes.ventas_diarias') }}" method="GET" class="d-flex justify-content-end align-items-center">
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control w-50 shadow-sm border-0 mr-2">
                <button type="submit" class="btn btn-dark px-4 shadow-sm">Consultar</button>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #28a745;">
                <small class="text-muted font-weight-bold text-uppercase">Total Recaudado</small>
                <h3 class="font-weight-bold text-success">${{ number_format($totalVendido, 0) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #17a2b8;">
                <small class="text-muted font-weight-bold text-uppercase">Ventas Realizadas</small>
                <h3 class="font-weight-bold text-info">{{ $numeroVentas }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-4">🕒 Flujo de Ventas por Hora</h5>
                    <div style="height: 350px;">
                        <canvas id="chartVentasHora"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-4">👨‍💼 Ranking de Empleados</h5>
                    <div class="list-group list-group-flush">
                        @forelse($ventasEmpleado as $ve)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center border-0">
                            <div>
                                <h6 class="mb-0 font-weight-bold">{{ $ve->name }}</h6>
                                <small class="text-muted">{{ $ve->cantidad_pedidos }} pedidos</small>
                            </div>
                            <span class="badge badge-success p-2">${{ number_format($ve->total_vendido, 0) }}</span>
                        </div>
                        @empty
                        <p class="text-center text-muted">No hay ventas registradas hoy.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartVentasHora').getContext('2d');


    // Convertir datos de PHP a JavaScript de forma segura
    const horasRaw = @json($ventasHora->pluck('hora'));
    const totales = @json($ventasHora->pluck('total'));

    // Formatear horas para que siempre se vean como "09:00"
    const horas = horasRaw.map(h => {
        let horaNumerica = parseInt(h);
        return (horaNumerica < 10 ? '0' + horaNumerica : horaNumerica) + ':00';
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: horas.map(h => h + ':00'),
            datasets: [{
                label: 'Ventas por Hora ($)',
                data: totales,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection