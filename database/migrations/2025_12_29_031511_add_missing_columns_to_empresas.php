<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingColumnsToEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'nombre_empresa')) $table->string('nombre_empresa')->nullable();
            if (!Schema::hasColumn('empresas', 'descripcion')) $table->text('descripcion')->nullable();
            if (!Schema::hasColumn('empresas', 'direccion')) $table->string('direccion')->nullable();
            if (!Schema::hasColumn('empresas', 'telefono')) $table->string('telefono')->nullable();
            if (!Schema::hasColumn('empresas', 'ciudad')) $table->string('ciudad')->nullable();
            if (!Schema::hasColumn('empresas', 'logo')) $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
