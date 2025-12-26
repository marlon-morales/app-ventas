<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido; // llame Pedido
use App\DetallePedido; //modelo para los detalles
use Auth;

class PedidoController extends Controller
{
    public function indexGestion() {
        $pedidos = \App\Pedido::with('detalles.producto')
                    ->where('estado', 'cocina') // Solo los que se pueden modificar/preparar
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('pedidos.gestion', compact('pedidos'));
    }

    // Función para cargar la vista de edición
    public function editar($id)
    {
        // 1. Buscamos el pedido con sus productos y categorías
        $pedido = \App\Pedido::with('detalles.producto')->findOrFail($id);

        // 2. Traemos todos los productos disponibles (igual que en el POS)
        $productos = \App\Producto::where('empresa_id', auth()->user()->empresa_id)->get();

        // 3. Pasamos el pedido para que la vista lo reconozca
        return view('pedidos.editar_pos', compact('pedido', 'productos'));
    }

    // Función para procesar la cancelación (Botón Rojo)
    public function cancelar($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = 'cancelado';
        $pedido->save();

        return redirect()->back()->with('success', 'Pedido cancelado.');
    }

    public function updatePedido(Request $request, $id)
    {
        // 1. Buscamos el pedido principal
        $pedido = \App\Pedido::findOrFail($id);
        $pedido->cliente_nombre = $request->cliente_nombre;
        $pedido->estado = 'cocina'; // Forzamos que siga siendo visible en cocina/modificar
        $pedido->detalles()->delete();

        // 2. ELIMINAMOS TODOS los detalles actuales de este pedido en la DB
        // Esto limpia el "pasado" del pedido para escribir el "presente" del carrito
        \App\DetallePedido::where('pedido_id', $id)->delete();

        $nuevoTotal = 0;

        // 3. Verificamos si el carrito envió productos
        if ($request->has('productos') && is_array($request->productos)) {
            foreach ($request->productos as $id_producto => $datos) {
                $cantidad = intval($datos['cantidad']);

                if ($cantidad > 0) {
                    $producto = \App\Producto::find($id_producto);
                    if ($producto) {
                        // 4. Creamos el nuevo registro de detalle
                        $detalle = new \App\DetallePedido();
                        $detalle->pedido_id = $id; // El ID del pedido que estamos editando
                        $detalle->producto_id = $id_producto;
                        $detalle->cantidad = $cantidad;
                        $detalle->precio_unitario = $producto->precio;
                        $detalle->save();

                        $nuevoTotal += ($producto->precio * $cantidad);
                    }
                }
            }
        }

        // 5. Actualizamos el total general del pedido
        $pedido->total = $nuevoTotal;
        $pedido->save();

        return redirect()->route('pedidos.gestion')->with('success', '✅ Pedido #' . $id . ' actualizado correctamente con todos los productos.');
    }

    public function vistaCocina() {
        $pedidos = \App\Pedido::with('detalles.producto')
                    ->where('estado', 'cocina') // Solo los que están en preparación
                    ->orderBy('created_at', 'asc')
                    ->get();
        return view('cocina.index', compact('pedidos'));
    }

    public function despachar($id) {
        // Usamos DB::table para forzar el guardado si el modelo falla
            \DB::table('pedidos')
                ->where('id_pedido', $id) //
                ->update(['estado' => 'listo']);

            return redirect()->route('cocina.index')->with('success', 'Pedido enviado a pagos.');
    }

    public function listaModificar()
    {
        $pedidos = \App\Pedido::whereIn('estado', ['pendiente', 'en_proceso'])
                    ->orderBy('created_at', 'desc')
                    .get();

        return view('pedidos.gestion', compact('pedidos')); // Ajusta el nombre de tu vista
    }

    public function vistaPagos() {
        $pedidos = \App\Pedido::with('detalles.producto')
                    ->where('estado', 'listo') // Solo los que ya salieron de cocina
                    ->orderBy('updated_at', 'desc')
                    ->get();
        return view('pedidos.pagos', compact('pedidos')); // Ajusta el nombre de tu vista de pagos
    }
}
