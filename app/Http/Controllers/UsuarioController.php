<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; // Revisa si tu modelo es App\User o App\Models\User
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('id_empresa', Auth::user()->id_empresa)->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rol' => 'required'
        ]);

        $user = new \App\User(); // O \App\Models\User
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->rol = $request->rol;

        // Tomamos el valor del administrador logueado
        $user->empresa_id = \Auth::user()->empresa_id;
        $user->id_empresa = \Auth::user()->id_empresa;
        // Permisos
        $user->p_productos = $request->has('p_productos') ? 1 : 0;
        $user->p_pedidos   = $request->has('p_pedidos')   ? 1 : 0;
        $user->p_cocina    = $request->has('p_cocina')    ? 1 : 0;
        $user->p_pagos     = $request->has('p_pagos')     ? 1 : 0;
        $user->p_informes  = $request->has('p_informes')  ? 1 : 0;
        $user->p_usuarios  = $request->has('p_usuarios')  ? 1 : 0;

        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        // Buscamos el usuario por su ID
        $usuario = \App\User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = \App\User::findOrFail($id);

        // Validación (email único excepto para este usuario)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'rol' => 'required'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        // Solo actualizamos la contraseña si el usuario escribió algo
        if ($request->filled('password')) {
            $usuario->password = \Hash::make($request->password);
        }

        // Actualizamos Permisos (1 si está marcado, 0 si no)
        $usuario->p_productos = $request->has('p_productos') ? 1 : 0;
        $usuario->p_pedidos   = $request->has('p_pedidos')   ? 1 : 0;
        $usuario->p_cocina    = $request->has('p_cocina')    ? 1 : 0;
        $usuario->p_pagos     = $request->has('p_pagos')     ? 1 : 0;
        $usuario->p_informes  = $request->has('p_informes')  ? 1 : 0;
        $usuario->p_usuarios  = $request->has('p_usuarios')  ? 1 : 0;

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function desactivar($id)
    {
        // Buscamos al usuario sin importar si es admin o empleado
        $usuario = \App\User::findOrFail($id);

        $usuario->activo = $usuario->activo ? 0 : 1;
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Estado de usuario actualizado.');
    }
}