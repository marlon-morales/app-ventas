<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'name', 'email', 'password', 'rol', 'id_empresa', 'empresa_id', 'activo',
        'p_productos', 'p_pedidos', 'p_cocina', 'p_pagos', 'p_informes', 'p_usuarios'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function esSuperAdmin()
    {
        return $this->rol === 'superadmin';
    }

    public function empresa()
    {

        return $this->belongsTo(\App\Empresa::class, 'id_empresa', 'id_empresa');
    }
}
