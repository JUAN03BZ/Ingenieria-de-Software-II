<?php
// app/Models/Cliente.php [web:84]

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'telefono',
        'user_id',
    ];
}
