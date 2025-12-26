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
        $fecha = $request->input('fecha', date('Y-m-d'));
        $horaFiltro = $request->input('hora');

        // 1. Base de la consulta especificando la tabla 'pedidos'
        // Usamos 'pedidos.created_at' y 'pedidos.estado' para evitar la ambigüedad
        $baseQuery = Pedido::whereDate('pedidos.created_at', $fecha)
                            ->whereRaw('LOWER(pedidos.estado) = ?', ['pagado']);

        if ($horaFiltro !== null && $horaFiltro !== '') {
            $baseQuery->whereRaw('EXTRACT(HOUR FROM pedidos.created_at) = ?', [$horaFiltro]);
        }

        // 2. Resumen General
        $resumen = (clone $baseQuery)
            ->selectRaw('SUM(pedidos.total) as gran_total, COUNT(*) as conteo')
            ->first();

        // 3. Ranking de Empleados (Aquí era donde fallaba por el JOIN)
        $ventasEmpleado = (clone $baseQuery)
            ->select(
                'users.name',
                DB::raw('SUM(pedidos.total) as total_vendido'),
                DB::raw('COUNT(pedidos.id_pedido) as cantidad_pedidos')
            )
            ->join('users', 'pedidos.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->get();

        // 4. Datos para la Gráfica
        $ventasHora = (clone $baseQuery)
            ->select(
                DB::raw('EXTRACT(HOUR FROM pedidos.created_at) as hora_num'),
                DB::raw('SUM(pedidos.total) as total_dinero'),
                DB::raw('COUNT(pedidos.id_pedido) as cantidad_pedidos')
            )
            ->groupBy('hora_num')
            ->orderBy('hora_num', 'asc')
            ->get();

        return view('informes.ventas_diarias', [
            'totalVendido' => $resumen->gran_total ?? 0,
            'numeroVentas' => $resumen->conteo ?? 0,
            'ventasEmpleado' => $ventasEmpleado,
            'ventasHora' => $ventasHora,
            'fecha' => $fecha,
            'horaFiltro' => $horaFiltro
        ]);
    }

    public function rankingProductos(Request $request)
    {
        $fecha = $request->input('fecha');

        // 1. Consulta rápida de IDs y cantidades
        $rankingData = DB::table('detalle_pedidos')
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id_pedido')
            ->select('detalle_pedidos.producto_id', DB::raw('SUM(detalle_pedidos.cantidad) as total'))
            ->whereRaw('LOWER(pedidos.estado) = ?', ['pagado'])
            ->when($fecha, function ($query, $fecha) {
                return $query->whereDate('pedidos.created_at', $fecha);
            })
            ->groupBy('detalle_pedidos.producto_id')
            ->orderBy('total', 'desc')
            ->get();

        if ($rankingData->isEmpty()) {
            return view('informes.ranking_productos', ['masVendidos' => [], 'menosVendidos' => [], 'fecha' => $fecha]);
        }

        // 2. Cargar modelos de productos (Usando la ruta correcta \App\Producto)
        $ids = $rankingData->pluck('producto_id');
        $productos = \App\Producto::whereIn('id_producto', $ids)->get()->keyBy('id_producto');

        // 3. Mapear resultados
        $resultado = $rankingData->map(function($item) use ($productos) {
            if (isset($productos[$item->producto_id])) {
                $p = $productos[$item->producto_id];
                $p->total_vendido = $item->total;
                return $p;
            }
        })->filter();

        $masVendidos = $resultado->take(10);
        $menosVendidos = $resultado->reverse()->take(10);

        return view('informes.ranking_productos', compact('masVendidos', 'menosVendidos', 'fecha'));
    }

    
}