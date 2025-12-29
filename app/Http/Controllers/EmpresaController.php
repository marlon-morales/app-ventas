<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{

    public function create() {
        return view('empresas.create');
    }

    public function index() {
        // Obtenemos todas las empresas ordenadas por ID de forma descendente (las más nuevas primero)
        $empresas = \App\Empresa::orderBy('id_empresa', 'asc')->get();

        // Para cada empresa, buscamos su administrador para mostrarlo en la tabla
        foreach ($empresas as $empresa) {
            $empresa->admin = \App\User::where('id_empresa', $empresa->id_empresa)
                                       ->where('rol', 'admin')
                                       ->first();
        }

        return view('empresas.index', compact('empresas'));
    }

    public function store(Request $request) {
        // 1. Manejo del Logo
        $nombreLogo = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nombreLogo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('logos'), $nombreLogo);
        }

        \DB::beginTransaction();
        try {
            // 2. Crear Empresa
            $empresa = new \App\Empresa(); // Asegúrate que la ruta sea correcta
            $empresa->nombre_empresa = $request->nombre_empresa;
            $empresa->logo = $nombreLogo;
            $empresa->descripcion = $request->descripcion;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->ciudad = $request->ciudad;
            $empresa->save();

            // 3. Crear Usuario Administrador (Instancia Manual para evitar el error de campos nulos)
            $user = new \App\User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->rol = 'admin'; // Forzamos que sea admin
            $user->id_empresa = $empresa->id_empresa; // Usamos el ID recién creado
            $user->empresa_id = $empresa->id_empresa;
            $user->activo = true;

            // Permisos explícitos
            $user->p_productos = true;
            $user->p_pedidos = true;
            $user->p_cocina = true;
            $user->p_pagos = true;
            $user->p_informes = true;
            $user->p_usuarios = true;

            $user->save(); // Al usar ->save() en una instancia nueva, Laravel DEBE enviar los datos

            \DB::commit();
            return redirect()->route('empresas.index')->with('success', '¡Empresa y Admin creados exitosamente!');

        } catch (\Exception $e) {
            \DB::rollback();
            // Esto te mostrará el error real si algo más falla
            return "Error detallado: " . $e->getMessage();
        }
    }

    // Añade estos métodos a tu EmpresaController.php

    public function edit($id) {
        $empresa = \App\Empresa::findOrFail($id);

        // Buscamos al admin intentando por ambos nombres de columna comunes
        $admin = \App\User::where('rol', 'admin')
                    ->where(function($query) use ($id) {
                        $query->where('id_empresa', $id)
                              ->orWhere('empresa_id', $id);
                    })->first();

        return view('empresas.edit', compact('empresa', 'admin'));
    }

    public function update(Request $request, $id) {
        $empresa = \App\Empresa::findOrFail($id);

        // 1. Actualizar Empresa
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nombreLogo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('logos'), $nombreLogo);
            $empresa->logo = $nombreLogo;
        }

        $empresa->nombre_empresa = $request->nombre_empresa;
        $empresa->descripcion = $request->descripcion;
        $empresa->direccion = $request->direccion;
        $empresa->telefono = $request->telefono;
        $empresa->ciudad = $request->ciudad;
        $empresa->save();

        // 2. Actualizar Usuario Admin (si existe)
        $admin = \App\User::where('id_empresa', $id)->where('rol', 'admin')->first();
        if($admin) {
            $admin->name = $request->name;
            $admin->email = $request->email;
            if($request->filled('password')) {
                $admin->password = \Hash::make($request->password);
            }
            $admin->save();
        }

        return redirect()->route('empresas.index')->with('success', 'Datos actualizados correctamente');
    }

}
