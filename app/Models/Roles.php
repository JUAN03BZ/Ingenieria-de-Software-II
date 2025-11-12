<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['name','description','permissions'];

    public $timestamps = true;

    protected $casts = [
        'permissions' => 'array', // requiere columna JSON en migración
    ];

    // Relación inversa: usuarios con este rol
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    // ¿El rol tiene un permiso?
    public function hasPermission(string $permission): bool
    {
        $perms = $this->permissions ?? [];
        return is_array($perms) && in_array($permission, $perms, true);
    }

    // Mapa de roles de sistema (opcional)
    public static function getSystemRoles(): array
    {
        return [
            'contratista'     => 'Contratista',
            'supervisor'      => 'Supervisor',
            'alcalde'         => 'Alcalde',
            'ordenador_gasto' => 'Ordenador del Gasto',
            'tesoreria'       => 'Tesorería',
            'contratacion'    => 'Contratación',
        ];
    }
}
