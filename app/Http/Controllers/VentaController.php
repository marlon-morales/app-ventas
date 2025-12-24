<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\DetallePedido;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $pedido = new \App\Pedido();
            $pedido->cliente_nombre = $request->cliente;
            $pedido->total = $request->total;
            $pedido->estado = 'cocina';
            $pedido->user_id = auth()->id();
            $pedido->empresa_id = auth()->user()->empresa_id;
            $pedido->save();

            foreach ($request->items as $item) {
                $detalle = new \App\DetallePedido();
                $detalle->pedido_id = $pedido->id_pedido;
                $detalle->producto_id = $item['id'];
                $detalle->cantidad = $item['qty'];
                $detalle->precio_unitario = $item['precio'];
                $detalle->save();
            }

            \DB::commit();
            return response()->json(['success' => true, 'pedido_id' => $pedido->id_pedido]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function index() {
        $productos = \App\Producto::where('empresa_id', auth()->user()->empresa_id)->get();
        // Agrupamos por categoría para el diseño
        $categorias = ['entrada', 'plato fuerte', 'asados', 'bebidas', 'cocteles', 'helados', 'postres'];
        // Obtenemos el último ID y le sumamos 1
        $ultimoPedido = \App\Pedido::max('id_pedido');
        $proximoPedido = $ultimoPedido ? $ultimoPedido + 1 : 1;

        return view('ventas.pos', compact('productos', 'categorias', 'proximoPedido'));
    }

}
