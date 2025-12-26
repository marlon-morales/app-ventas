@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body">
            <form action="{{ route('informes.ventas_diarias') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="font-weight-bold small">FECHA:</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold small">HORA ESPECÍFICA (0-23):</label>
                    <select name="hora" class="form-control">
                        <option value="">Ver Todo el Día</option>
                        @for($i=0; $i<24; $i++)
                            <option value="{{ $i }}" {{ (isset($horaFiltro) && $horaFiltro == $i && $horaFiltro !== '') ? 'selected' : '' }}>
                                {{ $i < 10 ? '0'.$i : $i }}:00
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">🔍 CONSULTAR</button>
                    <a href="{{ route('informes.ventas_diarias', ['fecha' => $fecha]) }}" class="btn btn-outline-secondary px-4 shadow-sm">📅 DÍA COMPLETO</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3 bg-white" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <h6 class="text-muted small font-weight-bold">RECAUDADO @if($horaFiltro !== null) ({{$horaFiltro}}:00) @endif</h6>
                    <h2 class="text-success font-weight-bold">${{ number_format($totalVendido ?? 0, 0) }}</h2>
                    <hr>
                    <h6 class="text-muted small font-weight-bold">TOTAL PEDIDOS</h6>
                    <h2 class="text-info font-weight-bold">{{ $numeroVentas ?? 0 }}</h2>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">👨‍💼 RANKING EMPLEADOS</h6>
                    <div class="list-group list-group-flush">
                        @forelse($ventasEmpleado as $ve)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>{{ $ve->name }} ({{ $ve->cantidad_pedidos }})</span>
                            <span class="font-weight-bold">${{ number_format($ve->total_vendido, 0) }}</span>
                        </div>
                        @empty
                        <p class="text-muted small">Sin ventas en este rango.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-4">📈 ANÁLISIS POR HORAS (Ventas vs Cantidad)</h6>
                    <div style="height: 400px;">
                        <canvas id="graficaMixta"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('graficaMixta').getContext('2d');

    // Obtenemos las horas y los datos
    const etiquetas = @json($ventasHora->pluck('hora_num')).map(h => h + ':00');
    const dataDinero = @json($ventasHora->pluck('total_dinero'));
    const dataPedidos = @json($ventasHora->pluck('cantidad_pedidos'));

    if (window.miGrafica) { window.miGrafica.destroy(); }

    window.miGrafica = new Chart(ctx, {
        data: {
            labels: etiquetas,
            datasets: [
                {
                    type: 'line',
                    label: 'Total Ventas ($)',
                    data: dataDinero,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    yAxisID: 'y',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5
                },
                {
                    type: 'bar',
                    label: 'Cant. Pedidos',
                    data: dataPedidos,
                    backgroundColor: 'rgba(23, 162, 184, 0.5)',
                    yAxisID: 'y1',
                    borderRadius: 5,
                    barThickness: 30 // Ajustamos el grosor para que se vea bien si es una sola barra
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Ventas ($)' }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Cantidad' }
                }
            }
        }
    });
});
</script>
@endsection