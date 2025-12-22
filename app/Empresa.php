<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    // Le decimos a Laravel el nombre real de la tabla
    protected $table = 'empresas';

    // Le decimos cuál es la llave primaria personalizada
    protected $primaryKey = 'id_empresa';

    // Campos que permitimos llenar masivamente
    protected $fillable = ['nombre_empresa', 'descripcion'];
}
