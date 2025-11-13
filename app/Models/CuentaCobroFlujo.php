<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaCobroFlujo extends Model
{
    protected $fillable = [
        'cuenta_cobro_id',
        'user_id',
        'rol',
        'accion',
        'comentario'
    ];

    public function cuenta()
    {
        return $this->belongsTo(CuentaCobro::class, 'cuenta_cobro_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
