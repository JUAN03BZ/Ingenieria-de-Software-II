<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaCobro extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla
    protected $table = 'cuentas_cobro';

    // Campos que pueden ser asignados masivamente
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
        'observaciones'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
