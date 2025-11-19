<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaCobro extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'cuentas_cobro';

    // Asignación masiva
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
        'estado',         // 'pendiente' | 'aprobada' | 'rechazada' | 'revision' | 'pagada'
        'observaciones',
    ];

    // Timestamps (por claridad)
    public $timestamps = true;

    // Casts
    protected $casts = [
        'fecha_emision' => 'datetime',
        'monto'         => 'decimal:2',
    ];

    // Relación con usuario creador
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con archivos de soporte
    public function archivos()
    {
        return $this->hasMany(\App\Models\ArchivoCuentaCobro::class, 'cuenta_cobro_id');
    }

    // Scope actividad reciente (reutilizable)
    public function scopeActividadRecienteForUser($query, int $userId, int $limit = 5, array $estados = ['aprobada','pendiente','rechazada'])
    {
        return $query->with(['user.role'])
            ->where('user_id', $userId)
            ->whereIn('estado', $estados)
            ->orderBy('updated_at', 'desc')
            ->limit($limit);
    }

    // Accesor etiqueta legible del estado
    public function getEstadoLabelAttribute(): string
    {
        $map = [
            'pendiente' => 'Pendiente',
            'aprobada'  => 'Aprobada',
            'rechazada' => 'Rechazada',
            'revision'  => 'En revisión',
            'pagada'    => 'Pagada',
        ];
        return $map[$this->estado] ?? ucfirst($this->estado ?? 'desconocido');
    }

    public function flujos()
    {
        return $this->hasMany(CuentaCobroFlujo::class, 'cuenta_cobro_id')->latest();
    }
}
