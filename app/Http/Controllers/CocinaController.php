<?php

namespace App\Http\Controllers;

use App\Pedido;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        // Obtenemos pedidos en estado 'cocina' ordenados por el más antiguo primero
        $pedidos = Pedido::with('detalles.producto')
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->where('estado', 'cocina')
                    ->orderBy('created_at', 'asc')
                    ->get();

        return view('cocina.index', compact('pedidos'));
    }

    public function marcarListo($id) {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = 'listo'; // Cambia el estado
        $pedido->save();

        return back()->with('status', '¡Pedido despachado!');
    }
}
