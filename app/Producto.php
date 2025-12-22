<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    // Campos que permitimos llenar desde el formulario
    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'imagen', 'empresa_id', 'categoria'
    ];
}