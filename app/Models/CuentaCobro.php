<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaCobro extends Model
{
    use HasFactory;

    protected $table = 'cuentas_cobro';

    protected $fillable = [
        'concepto_cobro',
        'periodo',
        'valor_cobro',
        'valor_bruto',
        'retencion_fuente',
        'otros_descuentos',
        'total_pagar',
        'forma_pago',
    ];
}
