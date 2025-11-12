<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CuentaCobro;

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
                return back()->with('error', 'Tu cuenta está pendiente de aprobación.');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->with('error', 'Credenciales inválidas.');
    }

    // Mostrar dashboard/menú principal
    public function dashboard()
    {
        $user = Auth::user();

        // KPIs del usuario autenticado (propias cuentas)
        $cuentasQuery = CuentaCobro::where('user_id', $user->id);

        $totalCuentas   = (clone $cuentasQuery)->count();
        $pagadas        = (clone $cuentasQuery)->where('estado', 'pagada')->count();
        $pendientes     = (clone $cuentasQuery)->where('estado', 'pendiente')->count();
        $totalFacturado = (clone $cuentasQuery)
            ->where('estado', 'pagada')
            ->where('fecha_emision', '>=', now()->startOfMonth())
            ->sum('monto');

        // Actividad reciente GLOBAL (todos ven las últimas cuentas del sistema)
        $actividadesRecientes = CuentaCobro::with(['user.role'])
            ->whereIn('estado', ['aprobada', 'pendiente', 'rechazada'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalCuentas',
            'pagadas',
            'pendientes',
            'totalFacturado',
            'actividadesRecientes'
        ));
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
