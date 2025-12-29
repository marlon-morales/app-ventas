<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmpresasTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Solo agrega los que te falten, Laravel ignorará los existentes
            if (!Schema::hasColumn('empresas', 'logo')) $table->string('logo')->nullable();
            if (!Schema::hasColumn('empresas', 'nombre_empresa')) $table->string('nombre_empresa');
            if (!Schema::hasColumn('empresas', 'descripcion')) $table->text('descripcion')->nullable();
            if (!Schema::hasColumn('empresas', 'ciudad')) $table->string('ciudad')->nullable();
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
