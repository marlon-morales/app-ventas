<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Permisos de menú
            $table->boolean('p_productos')->default(false);
            $table->boolean('p_pedidos')->default(false);
            $table->boolean('p_cocina')->default(false);
            $table->boolean('p_pagos')->default(false);
            $table->boolean('p_informes')->default(false);
            $table->boolean('p_usuarios')->default(false);
            // Empresa automática (asumiendo que usas un campo empresa_id o similar)
            $table->integer('id_empresa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['p_productos', 'p_pedidos', 'p_cocina', 'p_pagos', 'p_informes', 'p_usuarios']);
        });
    }
}
