<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
        $table->increments('id_producto'); // Llave primaria
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->decimal('precio', 10, 2);
        $table->string('imagen')->nullable(); // Guardaremos la ruta de la foto

        // Relación con la empresa (Importante para el sistema multi-negocio)
        $table->integer('empresa_id')->unsigned();
        $table->foreign('empresa_id')
              ->references('id_empresa')
              ->on('empresas')
              ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
