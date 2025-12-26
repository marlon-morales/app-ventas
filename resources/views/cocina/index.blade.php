@extends('layouts.app')

@section('content')
<div class="container-fluid bg-secondary py-4" style="min-height: 100vh;">
    <h2 class="text-white mb-4 text-center font-weight-bold">👨‍🍳 MONITOR DE PEDIDOS</h2>

    <div class="row">
        @foreach($pedidos as $pedido)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-lg border-0 order-card" data-start="{{ $pedido->created_at }}" style="border-radius: 15px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 pt-3">
                    <span class="badge badge-dark">#{{ $pedido->id_pedido }}</span>
                    <span class="text-muted small">Hora: {{ $pedido->created_at->format('H:i:s') }}</span>
                </div>

                <div class="card-body">
                    <h5 class="font-weight-bold text-center border-bottom pb-2">{{ $pedido->cliente_nombre }}</h5>

                    <div class="order-items-list mb-3">
                        @foreach($pedido->detalles as $detalle)
                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded shadow-sm">
                            <div class="mr-3 px-3 py-2 bg-dark text-white rounded-lg" style="font-size: 1.8rem; font-weight: 900; min-width: 60px; text-align: center;">
                                {{ $detalle->cantidad }}
                            </div>

                            <div class="flex-grow-1">
                                <span class="d-block font-weight-bold" style="font-size: 1.2rem; color: #333;">
                                    {{ $detalle->producto->nombre }}
                                </span>
                                <span class="badge badge-secondary text-uppercase">{{ $detalle->producto->categoria }}</span>
                            </div>

                            <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen) }}"
                                 style="width: 55px; height: 55px; object-fit: cover; border-radius: 10px;">
                        </div>
                        @endforeach
                    </div>

                    <div class="timer-box text-center py-2 mb-3 rounded-pill bg-white border border-secondary">
                        <span class="small d-block text-muted">Tiempo transcurrido:</span>
                        <span class="elapsed-timer font-weight-bold" style="font-size: 1.3rem;">00:00:00</span>
                    </div>

                    <div class="card-footer">
                        <form action="{{ route('pedidos.despachar', $pedido->id_pedido) }}" method="POST">
                            @csrf
                            <input type="submit" value="✅ DESPACHAR Y ENVIAR A CAJA" class="btn btn-success btn-block btn-lg rounded-pill font-weight-bold shadow-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function updateTimers() {
        const cards = document.querySelectorAll('.order-card');
        cards.forEach(card => {
            const startTime = new Date(card.getAttribute('data-start')).getTime();
            const now = new Date().getTime();
            const diff = now - startTime;

            // Calcular horas, minutos y segundos transcurridos
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const timerElement = card.querySelector('.elapsed-timer');
            timerElement.innerText =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            // Cambiar color según urgencia (ej: más de 10 min en rojo)
            if (minutes >= 10) {
                timerElement.classList.add('text-danger');
                card.style.border = "2px solid red";
            } else if (minutes >= 5) {
                timerElement.classList.add('text-warning');
            } else {
                timerElement.classList.add('text-success');
            }
        });
    }

    setInterval(updateTimers, 1000);
    updateTimers();
</script>
@endsection