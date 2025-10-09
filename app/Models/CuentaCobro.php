<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaCobro extends Model
{
    use HasFactory;

    protected $table = 'cuentas_cobro';

    protected $fillable = [
        'nombre_cobrador',
        'documento_cobrador',
        'direccion_cobrador',
        'telefono_cobrador',
        'email_cobrador',
        'nombre_cliente',
        'documento_cliente',
        'monto',
        'descripcion',
        'fecha_emision',
        'user_id',
        'estado',
    ];
}
