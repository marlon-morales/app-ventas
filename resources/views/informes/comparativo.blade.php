@extends('layouts.app')

@section('content')
<style>
    #report-stage {
        min-height: 450px;
        width: 100%;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .btn-group .btn { min-width: 130px; font-weight: bold; }

    @media print {
        body * { visibility: hidden; }
        #report-card, #report-card * { visibility: visible; }
        #report-card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; }
        .no-print { display: none !important; }
    }
</style>

<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="no-print text-center mb-4">
        <h2 class="font-weight-bold">📊 Centro de Reportes Únicos</h2>
        <p class="text-muted">Seleccione un informe para visualizarlo</p>
    </div>

    <div class="row mb-4 no-print">
        <div class="col-12 text-center">
            <div class="btn-group flex-wrap shadow-sm bg-white p-2" style="border-radius: 50px;">
                <button class="btn btn-outline-primary active rounded-pill mx-1" onclick="renderReport('kpi', this)">KPIs</button>
                <button class="btn btn-outline-primary rounded-pill mx-1" onclick="renderReport('dia', this)">Día Fuerte</button>
                <button class="btn btn-outline-primary rounded-pill mx-1" onclick="renderReport('tendencia', this)">Tendencia</button>
                <button class="btn btn-outline-primary rounded-pill mx-1" onclick="renderReport('comparativo', this)">Comparativo</button>
                <button class="btn btn-outline-primary rounded-pill mx-1" onclick="renderReport('proyeccion', this)">Proyección</button>
                <button class="btn btn-outline-primary rounded-pill mx-1" onclick="renderReport('empleados', this)">Empleados</button>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 mx-auto" id="report-card" style="border-radius: 20px; max-width: 950px;">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4">
            <h4 class="mb-0 font-weight-bold text-primary" id="current-title">Resumen de KPIs</h4>
            <button class="btn btn-danger no-print shadow-sm" onclick="window.print()">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </button>
        </div>
        <div class="card-body p-5">
            <div id="report-stage">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // PASO 1: Definir los datos en un objeto JavaScript para evitar duplicar IDs en el HTML
    const reportData = {
        kpi: `
            <div class="text-center w-100">
                <h5 class="text-muted">Crecimiento Mensual</h5>
                <h1 class="display-1 font-weight-bold {{ ($mesAnterior > 0 && ($mesActual - $mesAnterior) / $mesAnterior * 100) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($mesAnterior > 0 ? (($mesActual - $mesAnterior) / $mesAnterior) * 100 : 0, 1) }}%
                </h1>
                <div class="h3 mt-4">Facturado: <strong>${{ number_format($mesActual, 0) }}</strong></div>
            </div>`,
        dia: `
            <div class="text-center w-100">
                <h4 class="text-muted">Día de Mayores Ventas</h4>
                <h1 class="display-2 text-primary font-weight-bold">{{ strtoupper($diaFuerte->dia ?? 'N/A') }}</h1>
                <p class="h4">Ventas Acumuladas: \${{ number_format($diaFuerte->total ?? 0, 0) }}</p>
            </div>`,
        tendencia: `<div class="w-100"><canvas id="cnvTendencia" height="350"></canvas></div>`,
        comparativo: `<div class="w-100"><canvas id="cnvComparativo" height="350"></canvas></div>`,
        empleados: `<div class="w-100"><canvas id="cnvEmpleados" height="350"></canvas></div>`,
        proyeccion: `
            <div class="w-100">
                <h3 class="text-center mb-4">🔮 Proyección Estimada</h3>
                <table class="table table-bordered h4 text-center">
                    <tr class="bg-light"><td>Venta Hoy:</td><td>\${{ number_format($mesActual, 0) }}</td></tr>
                    <tr class="table-success"><td><strong>Meta Final:</strong></td><td><strong>\${{ number_format($proyeccion, 0) }}</strong></td></tr>
                </table>
            </div>`
    };

    function renderReport(type, btn) {
        const stage = document.getElementById('report-stage');

        // PASO 2: LIMPIEZA TOTAL - Vaciamos el escenario
        stage.innerHTML = '';

        // Actualizar botones
        document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // PASO 3: INYECTAR EL HTML - No clonamos, inyectamos texto nuevo
        stage.innerHTML = reportData[type];

        // Actualizar título
        const titles = { kpi: 'Resumen de KPIs', dia: 'Día de Mayor Venta', tendencia: 'Tendencia 30 Días', comparativo: 'Comparativo Mensual', proyeccion: 'Proyección de Cierre', empleados: 'Ventas por Empleado' };
        document.getElementById('current-title').innerText = titles[type];

        // PASO 4: DIBUJAR GRÁFICAS (solo si el elemento ya existe en el DOM)
        if(type === 'tendencia') drawChart('cnvTendencia', 'line', @json($tendencia30->pluck('fecha')), @json($tendencia30->pluck('total')), '#007bff');
        if(type === 'comparativo') drawChart('cnvComparativo', 'bar', ['Anterior', 'Actual'], [{{$mesAnterior}}, {{$mesActual}}], ['#6c757d', '#28a745']);
        if(type === 'empleados') drawChart('cnvEmpleados', 'bar', @json($empleadosMes->pluck('name')), @json($empleadosMes->pluck('total')), '#fd7e14');
    }

    function drawChart(canvasId, type, labels, data, color) {
        new Chart(document.getElementById(canvasId), {
            type: type,
            data: {
                labels: labels,
                datasets: [{ label: 'Ventas $', data: data, backgroundColor: color, borderColor: Array.isArray(color) ? '#fff' : color, fill: type === 'line' }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    // Cargar el primero por defecto al iniciar
    window.onload = () => {
        renderReport('kpi', document.querySelector('.btn-group .btn'));
    };
</script>
@endsection