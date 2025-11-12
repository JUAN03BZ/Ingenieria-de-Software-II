<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'is_approved'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_approved'       => 'boolean',
        ];
    }

    // Relación con Rol (tu modelo es Roles)
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    // Nombre del rol
    public function getRoleName(): string
    {
        $this->loadMissing('role');
        return $this->role ? $this->role->name : 'Sin rol';
    }

    // ¿Tiene alguno de los roles dados? (array o string)
    public function hasAnyRole(string|array $roles): bool
    {
        $this->loadMissing('role');
        if (!$this->role) return false;
        $roles = (array) $roles;
        return in_array($this->role->name, $roles, true);
    }

    // Alias: ¿tiene rol?
    public function hasRole(string|array $roles): bool
    {
        return $this->hasAnyRole($roles);
    }

    // Alias de compatibilidad
    public function checkRole(string|array $roles): bool
    {
        return $this->hasAnyRole($roles);
    }

    // Atajos por rol
    public function isAlcalde(): bool        { return $this->hasRole('alcalde'); }
    public function isContratista(): bool    { return $this->hasRole('contratista'); }
    public function isOrdenadorGasto(): bool { return $this->hasRole('ordenador_gasto'); }

    // Capacidades
    public function canApprovePayments(): bool
    {
        return $this->hasAnyRole(['alcalde', 'ordenador_gasto', 'tesoreria']);
    }

    public function canManageContracts(): bool
    {
        return $this->hasAnyRole(['contratacion', 'alcalde']);
    }

    // Permisos por rol (si Roles implementa hasPermission)
    public function hasPermission(string $permission): bool
    {
        $this->loadMissing('role');
        return $this->role?->hasPermission($permission) === true;
    }

    // Cuentas de cobro creadas por el usuario
    public function cuentasCobro()
    {
        return $this->hasMany(CuentaCobro::class, 'user_id');
    }
}
