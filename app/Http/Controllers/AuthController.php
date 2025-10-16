<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Mostrar dashboard/menú principal
    public function dashboard()
    {
        $user = Auth::user();
        // Obtener todas las cuentas de cobro del usuario
        $cuentas = \App\Models\CuentaCobro::where('user_id', $user->id)->get();

        $totalCuentas = $cuentas->count();
        $pagadas = $cuentas->where('estado', 'pagada')->count();
        $pendientes = $cuentas->where('estado', 'pendiente')->count();
        // Total facturado este mes
        $totalFacturado = $cuentas->where('estado', 'pagada')
            ->where('fecha_emision', '>=', now()->startOfMonth())
            ->sum('monto');

    $actividadesRecientes = $cuentas->sortByDesc('created_at')->take(5);
    return view('dashboard', compact('totalCuentas', 'pagadas', 'pendientes', 'totalFacturado', 'actividadesRecientes'));
    }

    // Procesar logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}