<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    /**
     * Get the users for this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Roles predefinidos del sistema
     */
    public static function getSystemRoles()
    {
        return [
            'contratista' => 'Contratista',
            'supervisor' => 'Supervisor',
            'alcalde' => 'Alcalde',
            'ordenador_gasto' => 'Ordenador del Gasto',
            'tesoreria' => 'Tesorería',
            'contratacion' => 'Contratación'
        ];
    }
}