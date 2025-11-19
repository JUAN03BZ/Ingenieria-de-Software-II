<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoCuentaCobro extends Model
{
    protected $table = 'archivo_cuenta_cobros';

    protected $fillable = [
        'cuenta_cobro_id',
        'nombre_original',
        'ruta',
    ];

    // Relación inversa a la cuenta
    public function cuenta()
    {
        return $this->belongsTo(CuentaCobro::class, 'cuenta_cobro_id');
    }
}
