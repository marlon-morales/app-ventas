<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación técnica de datos
        $request->validate([
            'nombre_empresa' => 'required|max:191',
            'admin_name'     => 'required|max:191',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear la Empresa
            $empresa = Empresa::create([
                'nombre_empresa' => $request->nombre_empresa,
                'descripcion'    => $request->descripcion,
            ]);

            // 3. Crear el Usuario Administrador ligado a esa Empresa
            User::create([
                'name'       => $request->admin_name,
                'email'      => $request->admin_email,
                'password'   => Hash::make($request->admin_password),
                'empresa_id' => $empresa->id_empresa, // Relación foránea
                'rol'        => 'admin',
            ]);

            DB::commit();
            return redirect()->route('login')->with('status', 'Empresa y Administrador creados con éxito. Ya puedes iniciar sesión.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error técnico: ' . $e->getMessage()]);
        }
    }
}
