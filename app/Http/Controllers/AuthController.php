<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login con verificación de aprobación
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_approved) {
                Auth::logout();
                return back()->with('error', 'Tu cuenta está pendiente de aprobación.'); // flash
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->with('error', 'Credenciales inválidas.');
    }

    // Mostrar dashboard/menú principal
    public function dashboard()
    {
        $user = Auth::user();
        $cuentas = \App\Models\CuentaCobro::where('user_id', $user->id)->get();

        $totalCuentas   = $cuentas->count();
        $pagadas        = $cuentas->where('estado', 'pagada')->count();
        $pendientes     = $cuentas->where('estado', 'pendiente')->count();
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
