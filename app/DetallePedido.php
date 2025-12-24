<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';
    protected $primaryKey = 'id_detalle';

    protected $fillable = ['pedido_id','producto_id','cantidad','precio_unitario'];

    public function producto() {
        return $this->belongsTo('App\Producto', 'producto_id', 'id_producto');
    }
}