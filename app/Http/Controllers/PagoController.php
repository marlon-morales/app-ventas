<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index() {
        // Solo mostramos lo que ya salió de cocina pero no se ha pagado
        $pedidos = \App\Pedido::where('estado', 'listo')
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->get();
        return view('pagos.index', compact('pedidos'));
    }

    public function finalizar($id) {
        $pedido = \App\Pedido::findOrFail($id);
        $pedido->estado = 'pagado'; // Esto lo saca de la vista de pagos
        $pedido->save();

        return back()->with('success', 'Pago procesado correctamente.');
    }
}
