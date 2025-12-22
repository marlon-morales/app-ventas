<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    // Bloqueamos el acceso a quien no esté logueado
    public function __construct() {
        $this->middleware('auth');
    }

    public function create() {
        return view('productos.create');
    }

    public function store(Request $request) {
        // 1. Validar datos
        $request->validate([
            'nombre' => 'required|max:191',
            'precio' => 'required|numeric',
            'categoria' => 'required',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $producto = new Producto($request->all());
        $producto->empresa_id = Auth::user()->empresa_id; // Lo ligamos a la empresa del usuario logueado
        $producto->categoria = $request->categoria;
        // 2. Manejo de la Imagen
        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/productos');
            $image->move($destinationPath, $name);
            $producto->imagen = $name; // Guardamos solo el nombre en la BD
        }

        $producto->save();


        return redirect()->route('home')->with('status', 'Producto guardado con categoría: ' . $request->categoria);
    }
}