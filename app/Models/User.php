<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con Roles (FK role_id)
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
        // Si tu modelo es singular usa Role::class
    }

    // Nombre del rol (seguro)
    public function getRoleName(): string
    {
        $this->loadMissing('role');
        return $this->role ? $this->role->name : 'Sin rol';
    }

    // ¿Tiene alguno de los roles dados?
    public function hasAnyRole(string|array $roles): bool
    {
        $this->loadMissing('role');
        if (!$this->role) {
            return false;
        }
        $roles = (array) $roles;
        return in_array($this->role->name, $roles, true);
    }

    // Alias: ¿tiene el rol exacto?
    public function hasRole(string|array $roles): bool
    {
        return $this->hasAnyRole($roles);
    }

    // Alias de compatibilidad
    public function checkRole(string|array $roles): bool
    {
        return $this->hasAnyRole($roles);
    }

    // ¿Es “alcalde”?
    public function isAlcalde(): bool
    {
        return $this->hasRole('alcalde');
    }

    // ¿Es “contratista”?
    public function isContratista(): bool
    {
        return $this->hasRole('contratista');
    }

    // ¿Es “ordenador de gasto”?
    public function isOrdenadorGasto(): bool
    {
        return $this->hasRole('ordenador_gasto');
    }

    // ¿Puede aprobar pagos?
    public function canApprovePayments(): bool
    {
        return $this->hasAnyRole(['alcalde', 'ordenador_gasto', 'tesoreria']);
    }

    // ¿Puede gestionar contratos?
    public function canManageContracts(): bool
    {
        return $this->hasAnyRole(['contratacion', 'alcalde']);
    }

    // Permisos vía rol - para Gates/Policies avanzadas
    public function hasPermission(string $permission): bool
    {
        $this->loadMissing('role');
        return $this->role?->hasPermission($permission) === true;
    }
}
