<?php
// app/Http/Controllers/CrearUsuario.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrearUsuario extends Controller
{
    public function showRegistrationForm() {
        return view('registro.registro');
    }

    public function register(Request $request) {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role_id'      => null,          // sin rol hasta aprobación
            'is_approved'  => false,         // pendiente
            'approved_at'  => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Gracias por tu registro. Espera tu aprobación.'); // mensaje flash
    }
}
