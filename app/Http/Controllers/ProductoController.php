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

    public function index()
    {
        // Obtenemos todos los productos del negocio
        $productos = \App\Producto::where('empresa_id', auth()->user()->empresa_id)->get();

        // Retornamos la vista que creamos en el Paso 1
        return view('productos.index', compact('productos'));
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

    public function edit($id)
    {
        $producto = \App\Producto::findOrFail($id);
        $categorias = ['Entradas', 'Plato Fuerte', 'Bebidas', 'Asados', 'Postres']; // O las que uses

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validamos que los datos sean correctos
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'precio'   => 'required|numeric',
            'categoria'=> 'required'
        ]);

        // 2. Buscamos el producto en la base de datos por su ID
        $producto = \App\Producto::findOrFail($id);


        // 3. Actualizamos los campos con lo que viene del formulario
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->categoria = $request->categoria;
        $producto->descripcion = $request->descripcion;

        // 4. Si el usuario subió una imagen nueva, la procesamos
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/productos'), $nombreImagen);
            $producto->imagen = $nombreImagen;
        }

        // 5. Guardamos los cambios definitivamente
        $producto->save();

        // 6. Redireccionamos a la lista con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', '✅ Producto actualizado correctamente.');
    }


}