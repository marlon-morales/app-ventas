<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'cliente_nombre',
        'total',
        'estado',
        'user_id',
        'empresa_id'
    ];

    // Relación: Un pedido tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany('App\DetallePedido', 'pedido_id', 'id_pedido');
    }
}