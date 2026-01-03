<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \App\Models\User::create([
            'name' => 'marlon',
            'email' => 'marlon.m.cobo@gmail.com',
            'password' => bcrypt('Cacahuate007'),
            'rol' => 'superadmin',
             'id_empresa' => '1',
              'empresa_id' => '1',
               'activo' => 'True',
               'p_productos' => 'True',
                'p_pedidos' => 'True',
                 'p_cocina' => 'True',
                 'p_pagos' => 'True',
                 'p_informes' => 'True',
                 'p_usuarios' => 'True',
        ]);
    }
}
