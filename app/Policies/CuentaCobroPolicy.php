<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CuentaCobro;

class CuentaCobroPolicy
{
    // Todos los roles listados pueden ver cualquier cuenta
    public function view(User $user, CuentaCobro $cuenta): bool
    {
        return $user->hasAnyRole(['contratista','supervisor','contratacion','tesoreria','ordenador_gasto','alcalde']);
    }

    // Editar: alcalde cualquiera; contratista solo la suya
    public function update(User $user, CuentaCobro $cuenta): bool
    {
        if ($user->isAlcalde()) return true;
        if ($user->isContratista() && $cuenta->user_id === $user->id) return true;
        return false;
    }

    // Eliminar: mismas reglas que update
    public function delete(User $user, CuentaCobro $cuenta): bool
    {
        if ($user->isAlcalde()) return true;
        if ($user->isContratista() && $cuenta->user_id === $user->id) return true;
        return false;
    }

    // Ver listado general (index)
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['contratista','supervisor','contratacion','tesoreria','ordenador_gasto','alcalde']);
    }

    // Crear: contratista (y opcionalmente alcalde)
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['contratista','alcalde']);
    }
}
