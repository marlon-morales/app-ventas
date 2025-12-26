<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido; // Asegúrate de que tu modelo se llame Pedido
use DB;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function ventasDiarias(Request $request)
    {
        // Capturar la fecha del filtro o usar la de hoy
        $fecha = $request->input('fecha', Carbon::today()->format('Y-m-d'));

        // 1. Total Dinero y Total Pedidos
        $resumen = Pedido::whereDate('created_at', $fecha)
                        ->where('estado', 'pagado') // Solo sumamos lo cobrado
                        ->selectRaw('SUM(total) as gran_total, COUNT(*) as conteo')
                        ->first();

        // 2. Ventas por Empleado (Relación con la tabla Users)
        $ventasEmpleado = Pedido::select('users.name', DB::raw('SUM(pedidos.total) as total_vendido'), DB::raw('COUNT(pedidos.id_pedido) as cantidad_pedidos'))
                        ->join('users', 'pedidos.user_id', '=', 'users.id')
                        ->whereDate('pedidos.created_at', $fecha)
                        ->where('pedidos.estado', 'pagado')
                        ->groupBy('users.name')
                        ->get();

        // 3. Ventas por Hora (Agrupadas para la gráfica)
        $ventasHora = Pedido::select(
                        DB::raw('EXTRACT(HOUR FROM created_at) as hora'),
                        DB::raw('SUM(total) as total')
                    )
                    ->whereDate('created_at', $fecha)
                    ->where('estado', 'pagado')
                    ->groupBy('hora')
                    ->orderBy('hora', 'asc')
                    ->get();

        return view('informes.ventas_diarias', [
            'totalVendido' => $resumen->gran_total ?? 0,
            'numeroVentas' => $resumen->conteo ?? 0,
            'ventasEmpleado' => $ventasEmpleado,
            'ventasHora' => $ventasHora,
            'fecha' => $fecha
        ]);
    }
}