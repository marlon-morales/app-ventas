<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas'; // Aseguramos el nombre de la tabla
    protected $guarded = [];       // Desbloquea todos los campos para guardar

    // Le decimos cuál es la llave primaria personalizada
    protected $primaryKey = 'id_empresa';

    // Campos que permitimos llenar masivamente
    protected $fillable = ['nombre_empresa', 'logo', 'descripcion', 'direccion', 'telefono', 'ciudad'];
}
